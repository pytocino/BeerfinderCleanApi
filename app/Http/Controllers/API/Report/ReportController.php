<?php

namespace App\Http\Controllers\API\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Gestión de Reportes
 *
 * APIs para gestionar reportes de contenido inapropiado (solo administradores)
 */
class ReportController extends Controller
{
    /**
     * Listar reportes
     * 
     * Obtiene un listado paginado de reportes con opciones de filtrado.
     *
     * @authenticated
     * @role admin
     * 
     * @queryParam status string Filtrar por estado (pending, reviewed, rejected, actioned). Example: pending
     * @queryParam type string Filtrar por tipo de contenido (comment, check_in, etc.). Example: comment
     * @queryParam per_page integer Elementos por página (5-50). Example: 15
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "reason": "offensive",
     *       "details": "Contiene lenguaje ofensivo e insultos.",
     *       "status": "pending",
     *       "reporter": {
     *         "id": 2,
     *         "name": "Juan Pérez"
     *       },
     *       "content_type": "comment",
     *       "content_id": 105,
     *       "created_at": "2023-04-18T19:15:00.000000Z"
     *     }
     *   ],
     *   "links": {...},
     *   "meta": {...}
     * }
     */
    public function index(Request $request): JsonResponse
    {
        // Verificar que el usuario sea administrador
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,reviewed,rejected,actioned',
            'type' => 'nullable|string',
            'per_page' => 'nullable|integer|min:5|max:50',
        ]);

        $query = Report::with('user:id,name');

        // Aplicar filtros
        if (isset($validated['status'])) {
            $query->where('status', '=', $validated['status']);
        }

        if (isset($validated['type'])) {
            $query->where('reportable_type', '=', $validated['type']);
        }

        // Ordenar por fecha de creación (más recientes primero)
        $query->orderBy('created_at', 'desc');

        $perPage = $validated['per_page'] ?? 15;
        $reports = $query->paginate($perPage);

        return response()->json([
            'data' => ReportResource::collection($reports),
            'links' => $reports->toArray()['links'],
            'meta' => $reports->toArray(),
        ]);
    }

    /**
     * Obtener detalles de un reporte
     * 
     * Obtiene información detallada de un reporte específico.
     *
     * @authenticated
     * @role admin
     * 
     * @urlParam id integer required ID del reporte. Example: 1
     *
     * @response {
     *   "data": {
     *     "id": 1,
     *     "reason": "offensive",
     *     "details": "Contiene lenguaje ofensivo e insultos.",
     *     "status": "pending",
     *     "reporter": {
     *       "id": 2,
     *       "name": "Juan Pérez"
     *     },
     *     "content_type": "comment",
     *     "content_id": 105,
     *     "created_at": "2023-04-18T19:15:00.000000Z",
     *     "admin_notes": null
     *   },
     *   "reported_content": {
     *     "id": 105,
     *     "content": "Este es un comentario ofensivo que ha sido reportado.",
     *     "user": {
     *       "id": 3,
     *       "name": "Ana García"
     *     }
     *   }
     * }
     * 
     * @response 404 {
     *   "message": "No se ha encontrado el reporte solicitado."
     * }
     */
    public function show(Request $request, $id): JsonResponse
    {
        // Verificar que el usuario sea administrador
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        try {
            // Obtener el reporte con relaciones
            $report = Report::with(['user:id,name', 'reviewer:id,name'])->findOrFail($id);

            // Cargar el contenido reportado según su tipo
            $reportedContent = null;

            if ($report->reportable_type === 'App\\Models\\Comment') {
                $comment = \App\Models\Comment::with('user:id,name')->find($report->reportable_id);
                if ($comment) {
                    $reportedContent = [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                        ],
                    ];
                }
            }
            // Aquí puedes añadir más tipos de contenido reportable

            return response()->json([
                'data' => new ReportResource($report),
                'reported_content' => $reportedContent
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el reporte solicitado.'], 404);
        }
    }

    /**
     * Actualizar estado de un reporte
     * 
     * Actualiza el estado de un reporte y opcionalmente agrega notas administrativas.
     *
     * @authenticated
     * @role admin
     * 
     * @urlParam id integer required ID del reporte. Example: 1
     * @bodyParam status string required Nuevo estado (reviewed, rejected, actioned). Example: reviewed
     * @bodyParam admin_notes string Notas administrativas sobre la resolución. Example: Contenido revisado y advertencia enviada al usuario.
     *
     * @response {
     *   "message": "Reporte actualizado correctamente.",
     *   "data": {
     *     "id": 1,
     *     "status": "reviewed",
     *     "resolved_at": "2023-04-19T10:30:00.000000Z",
     *     "admin_notes": "Contenido revisado y advertencia enviada al usuario."
     *   }
     * }
     * 
     * @response 404 {
     *   "message": "No se ha encontrado el reporte solicitado."
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Verificar que el usuario sea administrador
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        try {
            $validated = $request->validate([
                'status' => 'required|string|in:reviewed,rejected,actioned',
                'admin_notes' => 'nullable|string|max:1000',
            ]);

            $report = Report::findOrFail($id);

            // Actualizar el reporte
            $report->update([
                'status' => $validated['status'],
                'reviewed_by' => $request->user()->id,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'resolved_at' => now(),
            ]);

            return response()->json([
                'message' => 'Reporte actualizado correctamente.',
                'data' => [
                    'id' => $report->id,
                    'status' => $report->status,
                    'resolved_at' => $report->resolved_at->format('Y-m-d\TH:i:s.u\Z'),
                    'admin_notes' => $report->admin_notes,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se ha encontrado el reporte solicitado.'], 404);
        }
    }
}

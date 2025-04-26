<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    // Puedes agregar métodos personalizados aquí si lo necesitas,
    // pero no declares $fillable ni relaciones personalizadas,
    // ya que Laravel maneja la estructura estándar.
}

// types.ts

// Usuario
export interface User {
    id: number;
    name: string;
    username: string;
    profile_picture: string | null;
    bio: string | null;
    location: string | null;
    birthdate: string | null;
    website: string | null;
    phone: string | null;
    instagram: string | null;
    twitter: string | null;
    facebook: string | null;
    private_profile: boolean;
    allow_mentions: boolean;
    email_notifications: boolean;
    last_active_at: string | null;
    email_verified_at?: string | null;
    email?: string;
    created_at: string;
    updated_at: string;
    followers_count?: number | null;
    following_count?: number | null;
    posts_count?: number | null;
    is_following?: boolean;
}

// Cervecería
export interface Brewery {
    id: number;
    name: string;
    country: string;
    logo_url: string | null;
    created_at: string;
    updated_at: string;
}

// Estilo de cerveza
export interface BeerStyle {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

// Cerveza
export interface Beer {
    id: number;
    name: string;
    brewery: Brewery;
    style: BeerStyle;
    abv: number;
    ibu: number;
    description: string | null;
    image_url: string | null;
    rating_avg: number;
    check_ins_count: number;
    is_favorite: boolean;
    created_at: string;
    updated_at: string;
}

// Check-in
export interface CheckIn {
    id: number;
    user_id: number;
    beer_id: number;
    rating: number;
    comment: string | null;
    created_at: string;
    updated_at: string;
}

// Comentario
export interface Comment {
    id: number;
    user_id: number;
    post_id: number;
    content: string;
    created_at: string;
    updated_at: string;
}

// Favorito
export interface Favorite {
    id: number;
    user_id: number;
    beer_id: number;
    created_at: string;
    updated_at: string;
}

// Seguimiento
export interface Follow {
    id: number;
    follower_id: number;
    following_id: number;
    accepted: boolean;
    created_at: string;
    updated_at: string;
}

// Like
export interface Like {
    id: number;
    user_id: number;
    post_id: number;
    created_at: string;
    updated_at: string;
}

// Ubicación
export interface Location {
    id: number;
    name: string;
    address: string;
    latitude: number;
    longitude: number;
    created_at: string;
    updated_at: string;
}

// Mensaje
export interface Message {
    id: number;
    sender_id: number;
    receiver_id: number;
    content: string;
    is_read: boolean;
    created_at: string;
    updated_at: string;
}

// Notificación
export interface Notification {
    id: number;
    user_id: number;
    type: string;
    data: any;
    read_at: string | null;
    created_at: string;
    updated_at: string;
}

// Publicación
export interface Post {
    id: number;
    user_id: number;
    content: string;
    image_url: string | null;
    created_at: string;
    updated_at: string;
    comments_count?: number;
    likes_count?: number;
    is_liked?: boolean;
}

// Reporte
export interface Report {
    id: number;
    reportable_id: number;
    reportable_type: string;
    user_id: number;
    reason: string;
    admin_notes: string | null;
    resolved_at: string | null;
    created_at: string;
    updated_at: string;
}

// Recursos para API (ejemplo para paginación)
export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

// Respuestas API estándar
export interface ApiResponse<T> {
    status: 'success' | 'error';
    message: string | null;
    data: T | null;
}
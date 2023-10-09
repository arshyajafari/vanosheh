<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class MessageModel extends Model {
        use HasFactory;

        protected $table = 'messages';
        protected $fillable = [
            'user_name',
            'profile',
            'text',
            'is_seen',
        ];
        protected $casts = [
            'is_seen' => 'boolean'
        ];
    }

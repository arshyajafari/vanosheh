<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Model;

    class MessageModel extends Model {
        use HasFactory;

        protected $table = 'messages';
        protected $fillable = [
            'text',
        ];

        public function messageMemberPivot(): HasMany {
            return $this->hasMany(MessageMemberPivotModel::class, 'message_id', 'id');
        }
    }

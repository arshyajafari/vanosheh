<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Model;

    class MessageMemberPivotModel extends Model {
        use HasFactory;

        protected $table = 'message_member_pivot';
        protected $fillable = [
            'message_id',
            'member_id',
        ];

        public function message(): BelongsTo {
            return $this->belongsTo(MessageModel::class, "message_id", "id");
        }

        public function member(): BelongsTo {
            return $this->belongsTo(MemberModel::class, "member_id", "id");
        }
    }

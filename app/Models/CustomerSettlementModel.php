<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use \Illuminate\Database\Eloquent\Relations\BelongsTo;

    class CustomerSettlementModel extends Model {
        use HasFactory;

        protected $table = 'customer_settlement';
        protected $fillable = [
            'customer_id',
            'member_id',
            'payment_type',
            'bank_title',
            'account_number',
            'due_date',
            'cheque_number',
            'cheque_status',
            'submit_number',
            'received_amount',
            'discount',
            'amount',
            'description',
        ];

        public function customer(): BelongsTo {
            return $this->belongsTo(CustomerModel::class, "customer_id", "id");
        }

        public function member(): BelongsTo {
            return $this->belongsTo(MemberModel::class, "member_id", "id");
        }
    }

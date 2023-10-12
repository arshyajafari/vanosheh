<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Model;

    class MemberModel extends Model {
        use HasFactory;

        protected $table = 'members';
        protected $fillable = [
            'full_name',
            'national_code',
            'type_activity',
            'city',
            'phone_number',
            'social_number',
            'profile_picture',
            'national_card_picture',
            'password',
            'is_block',
            'is_primary',
            'privileges',
            'most_order_count',
            'last_order_count',
            'most_sold',
            'last_sold',
            'most_expensive',
            'last_expensive',
            'most_sold_goods',
            'last_sold_goods',
        ];
        protected $hidden = [
            'password',
            'created_at',
            'updated_at'
        ];
        protected $casts = [
            'is_primary' => 'boolean',
            'privileges' => 'object',
        ];
        public static $privileges_list = [
            'manage_members',
        ];

        public static function fix_privileges(object $temp_privileges, $privileges = null) {
            if (!is_object($privileges)) {
                $privileges = (object)[];
                foreach (self::$privileges_list as $privilege) {
                    $privileges->$privilege = false;
                }
            }
            foreach ($temp_privileges as $privilege => $value) {
                if (isset($privileges->$privilege)) {
                    $privileges->$privilege = (bool)$temp_privileges->$privilege;
                }
            }
            return $privileges;
        }

        public function order(): HasMany {
            return $this->hasMany(OrderModel::class, 'member_id', 'id');
        }

        public function settlement(): HasMany {
            return $this->hasMany(CustomerSettlementModel::class, 'member_id', 'id');
        }

        public function messageMemberPivot(): HasMany {
            return $this->hasMany(MessageMemberPivotModel::class, 'member_id', 'id');
        }

        public function returnOfGoods(): HasMany {
            return $this->hasMany(ReturnProductModel::class, 'member_id', 'id');
        }
    }

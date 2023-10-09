<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
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
            'is_primary',
            'privileges'
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
    }

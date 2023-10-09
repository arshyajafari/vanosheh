<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class AdminModel extends Model {
        use HasFactory;

        protected $table = 'admins';
        protected $fillable = [
            'full_name',
            'national_code',
            'type_activity',
            'phone_number',
            'national_card_picture',
            'user_name',
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
            'manage_users',
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

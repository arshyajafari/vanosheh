<?php

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use Genocide\Radiocrud\Exceptions\CustomException;
    use Illuminate\Support\Facades\Hash;
    use Laravel\Sanctum\NewAccessToken;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Str;
    use App\Models\MemberModel;

    class MemberAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(MemberModel::class)
                ->setResource(MemberResource::class)
                ->setValidationRules([
                    'storeByAdmin' => [
                        'full_name' => ['required', 'string', 'max:300'],
                        'national_code' => ['required', 'string', 'max:25'],
                        'type_activity' => ['required', 'string', 'max:100'],
                        'phone_number' => ['nullable', 'string', 'max:50'],
                        'profile_picture' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'national_card_picture' => ['required', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'password' => ['required', 'string', 'max:150'],
                        'privileges' => ['array', 'max:' . count(MemberModel::$privileges_list)],
                        'most_order_count' => ['required', 'integer', 'between:0,1000000000'],
                        'last_order_count' => ['required', 'integer', 'between:0,1000000000'],
                        'most_sold' => ['required', 'integer', 'between:0,1000000000'],
                        'last_sold' => ['required', 'integer', 'between:0,1000000000'],
                        'most_expensive' => ['required', 'integer', 'between:0,1000000000'],
                        'last_expensive' => ['required', 'integer', 'between:0,1000000000'],
                        'most_sold_goods' => ['required', 'integer', 'between:0,1000000000'],
                        'last_sold_goods' => ['required', 'integer', 'between:0,1000000000'],
                    ],
                    'updateByAdmin' => [
                        'full_name' => ['string', 'max:300'],
                        'national_code' => ['string', 'max:25'],
                        'type_activity' => ['string', 'max:100'],
                        'phone_number' => ['string', 'max:50'],
                        'profile_picture' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'national_card_picture' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'password' => ['string', 'max:150'],
                        'privileges' => ['array', 'max:' . count(MemberModel::$privileges_list)],
                        'most_order_count' => ['integer', 'between:0,1000000000'],
                        'last_order_count' => ['integer', 'between:0,1000000000'],
                        'most_sold' => ['integer', 'between:0,1000000000'],
                        'last_sold' => ['integer', 'between:0,1000000000'],
                        'most_expensive' => ['integer', 'between:0,1000000000'],
                        'last_expensive' => ['integer', 'between:0,1000000000'],
                        'most_sold_goods' => ['integer', 'between:0,1000000000'],
                        'last_sold_goods' => ['integer', 'between:0,1000000000'],
                    ],
                    'login' => [
                        'national_code' => ['required', 'string', 'max:25'],
                        'password' => ['required', 'string', 'max:150']
                    ],
                    'getQuery' => [
                        'search' => 'string|max:100'
                    ],
                    'updateInfo' => [
                        'new_password' => ['string', 'max:150'],
                        'profile_picture' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                    ]
                ])
                ->setCasts([
                    'profile_picture' => ['nullable', 'file'],
                    'national_card_picture' => ['required', 'file'],
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%");
                    },
                ]);
            foreach (MemberModel::$privileges_list as $privilege) {
                $this->validationRules['store']["privileges.$privilege"] = 'boolean';
                $this->validationRules['update']["privileges.$privilege"] = 'boolean';
            }
            parent::__construct();
        }

        public function store(array $data, callable $storing = null): mixed {
            if (MemberModel::where('national_code', $data['national_code'])->exists()) {
                throw new CustomException('this national_code is already taken', 986585);
            }
            $data['password'] = Hash::make($data['password']);
            $data['privileges'] = MemberModel::fix_privileges(
                (object)(!isset($data['privileges']) ? [] : $data['privileges'])
            );
            return parent::store($data, $storing);
        }

        protected function uploadFile(UploadedFile $file, string $path = '/uploads', string $fieldName = null): string {
            if (empty($path)) {
                $path = '/uploads';
            }
            $path = "$path/" . base64_encode(Str::random(32));
            return $file->storeAs($path, $file->getClientOriginalName());
        }

        public function login(array $data): NewAccessToken {
            $member = $this->model::where('national_code', $data['national_code'])->first();
            if (!empty($member)) {
                if (Hash::check($data['password'], $member->password)) {
                    return $member->createToken('auth_token');
                }
            }
            throw new CustomException('name or password is wrong', 2, 401);
        }

        public function loginByRequest(): NewAccessToken {
            return $this->login(
                $this->getDataFromRequest()
            );
        }

        public function update(array $updateData, callable $updating = null): bool|int {
            if (is_null($updating)) {
                $updating = function ($eloquent, &$update_data) {
                    $member = $this->getFirstByEloquent($eloquent);
                    if ($member->is_primary) {
                        throw new CustomException('primary accounts can not be edited', 11, 400);
                    }
                    if (
                        isset($update_data['national_code'])
                        &&
                        $this->model::where('national_code', $update_data['national_code'])
                            ->where('id', '!=', $member->id)
                            ->count() > 0
                    ) {
                        throw new CustomException('this national_code is already taken', 6, 400);
                    }
                    if (array_key_exists('profile_picture', $update_data) && is_file($member->profile_picture)) {
                        unlink($member->profile_picture);
                    }
                    if (array_key_exists('national_card_picture', $update_data) && is_file($member->national_card_picture)) {
                        unlink($member->national_card_picture);
                    }
                    if (isset($update_data['privileges'])) {
                        $update_data['privileges'] = MemberModel::fix_privileges(
                            (object)$update_data['privileges'],
                            MemberModel::fix_privileges(
                                (object)$member->privileges
                            )
                        );
                        $update_data['privileges'] = (array)$update_data['privileges'];
                    }
                    isset($update_data['password']) && $update_data['password'] = Hash::make($update_data['password']);
                };
            }
            return parent::update($updateData, $updating);
        }

        public function updateInfoByRequest(): bool|int {
            return $this
                ->setValidationRule('updateInfo')
                ->updateByRequest();
        }
    }

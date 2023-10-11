<?php

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use Genocide\Radiocrud\Exceptions\CustomException;
    use Illuminate\Support\Facades\Hash;
    use Laravel\Sanctum\NewAccessToken;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Str;
    use App\Models\AdminModel;

    class AdminAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(AdminModel::class)
                ->setResource(AdminResource::class)
                ->setValidationRules([
                    'store' => [
                        'full_name' => ['required', 'string', 'max:300'],
                        'national_code' => ['required', 'string', 'max:25'],
                        'type_activity' => ['required', 'string', 'max:100'],
                        'phone_number' => ['nullable', 'string', 'max:50'],
                        'national_card_picture' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'user_name' => ['required', 'string', 'max:50', 'unique:admins'],
                        'password' => ['required', 'string', 'max:150'],
                        'privileges' => ['array', 'max:' . count(AdminModel::$privileges_list)]
                    ],
                    'update' => [
                        'full_name' => ['string', 'max:300'],
                        'national_code' => ['string', 'max:25'],
                        'type_activity' => ['string', 'max:100'],
                        'phone_number' => ['string', 'max:50'],
                        'national_card_picture' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'user_name' => ['string', 'max:50', 'unique:admins'],
                        'password' => ['string', 'max:150'],
                        'privileges' => ['array', 'max:' . count(AdminModel::$privileges_list)]
                    ],
                    'login' => [
                        'user_name' => ['required', 'string', 'max:50'],
                        'password' => ['required', 'string', 'max:150'],
                    ],
                    'getQuery' => [
                        'search' => 'string|max:100'
                    ]
                ])->setCasts([
                    'national_card_picture' => ['nullable', 'file'],
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%");
                    },
                ]);
            foreach (AdminModel::$privileges_list as $privilege) {
                $this->validationRules['store']["privileges.$privilege"] = 'boolean';
                $this->validationRules['update']["privileges.$privilege"] = 'boolean';
            }
            parent::__construct();
        }

        public function store(array $data, callable $storing = null): mixed {
            if (AdminModel::where('national_code', $data['national_code'])->exists()) {
                throw new CustomException('this national_code is already taken', 986585);
            }
            $data['password'] = Hash::make($data['password']);
            $data['privileges'] = AdminModel::fix_privileges(
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
            $admin = $this->model::where('user_name', $data['user_name'])->first();
            if (!empty($admin)) {
                if (Hash::check($data['password'], $admin->password)) {
                    return $admin->createToken('auth_token');
                }
            }
            throw new CustomException('name or password is wrong', 2, 401);
        }

        public function loginByRequest(): NewAccessToken {
            return $this->login(
                $this->getDataFromRequest()
            );
        }

        public function getByRequestAndEloquent(): array {
            return parent::getByRequestAndEloquent();
        }

        public function getById(string $id): object {
            return parent::getById($id);
        }

        public function update(array $updateData, callable $updating = null): bool|int {
            if (is_null($updating)) {
                $updating = function ($eloquent, &$update_data) {
                    $admin = $this->getFirstByEloquent($eloquent);
                    if ($admin->is_primary) {
                        throw new CustomException('primary accounts can not be edited', 11, 400);
                    }
                    if (
                        isset($update_data['user_name'])
                        &&
                        $this->model::where('user_name', $update_data['user_name'])
                            ->where('id', '!=', $admin->id)
                            ->count() > 0
                    ) {
                        throw new CustomException('this user_name is already taken', 6, 400);
                    }
                    if (isset($update_data['privileges'])) {
                        $update_data['privileges'] = AdminModel::fix_privileges(
                            (object)$update_data['privileges'],
                            AdminModel::fix_privileges(
                                (object)$admin->privileges
                            )
                        );
                        $update_data['privileges'] = (array)$update_data['privileges'];
                    }
                    isset($update_data['password']) && $update_data['password'] = Hash::make($update_data['password']);
                };
            }
            return parent::update($updateData, $updating);
        }

        public function deleteById(string $id, callable $deleting = null): mixed {
            $deleting = function (&$eloquent) use ($deleting) {
                foreach ($eloquent->get() as $admin) {
                    if ($admin->is_primary) {
                        throw new CustomException('primary accounts can not be edited', 6, 400);
                    }
                }
                if (is_callable($deleting)) {
                    $deleting($eloquent);
                }
            };
            return parent::deleteById($id, $deleting);
        }
    }

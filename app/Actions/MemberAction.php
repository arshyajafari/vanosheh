<?php

    use Genocide\Radiocrud\Exceptions\CustomException;
    use Genocide\Radiocrud\Services\ActionService\ActionService;
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
                    ],
                    'login' => [
                        'national_code' => ['required', 'string', 'max:25'],
                        'password' => ['required', 'string', 'max:150']
                    ],
                    'getQuery' => [
                        'search' => 'string|max:100'
                    ],
                    'changePassword' => [
                        'current_password' => ['string', 'max:150'],
                        'new_password' => ['required', 'string', 'max:150']
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

        public function getByRequestAndEloquent(): array {
            return parent::getByRequestAndEloquent();
        }

        public function getById(string $id): object {
            return parent::getById($id);
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

        public function deleteById(string $id, callable $deleting = null): mixed {
            $deleting = function (&$eloquent) use ($deleting) {
                foreach ($eloquent->get() as $member) {
                    if ($member->is_primary) {
                        throw new CustomException('primary accounts can not be edited', 6, 400);
                    }
                }
                if (is_callable($deleting)) {
                    $deleting($eloquent);
                }
            };
            return parent::deleteById($id, $deleting);
        }

        public function changePassword(MemberModel $member, array $data): MemberModel {
            throw_if(
                !$member->should_change_password && (!isset($data['current_password']) || !Hash::check($data['current_password'], $member->password)),
                CustomException::class,
                'current password should be set and should be equal to current password of student',
                '923686',
                400
            );
            $member->should_change_password = false;
            $member->password = Hash::make($data['new_password']);
            $member->save();
            return $member;
        }

        public function changePasswordByRequest(): MemberModel {
            return $this->changePassword($this->request->user(), $this->getDataFromRequest());
        }
    }

<?php

    use Genocide\Radiocrud\Exceptions\CustomException;
    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use Illuminate\Http\UploadedFile;
    use App\Models\CustomerModel;
    use Illuminate\Support\Str;

    class CustomerAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(CustomerModel::class)
                ->setResource(CustomerResource::class)
                ->setValidationRules([
                    'store' => [
                        'full_name' => ['required', 'string', 'max:300'],
                        'national_code' => ['required', 'string', 'max:25'],
                        'economic_code' => ['nullable', 'string', 'max:25'],
                        'phone_number' => ['nullable', 'string', 'max:50'],
                        'telephone_number' => ['nullable', 'string', 'max:50'],
                        'city' => ['required', 'string', 'max:100'],
                        'address' => ['nullable', 'string', 'max:1500'],
                        'file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                    ],
                    'update' => [
                        'full_name' => ['string', 'max:300'],
                        'national_code' => ['string', 'max:25'],
                        'economic_code' => ['string', 'max:25'],
                        'phone_number' => ['string', 'max:50'],
                        'telephone_number' => ['string', 'max:50'],
                        'city' => ['string', 'max:100'],
                        'address' => ['string', 'max:1500'],
                        'file' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                    ],
                    'getQuery' => [
                        'search' => 'string|max:300'
                    ],
                ])
                ->setCasts([
                    'file' => ['nullable', 'file'],
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%")
                            ->orWhere('national_code', 'LIKE', "%{$query['search']}%")
                            ->orWhere('city', 'LIKE', "%{$query['search']}%");
                    },
                ]);
            parent::__construct();
        }

        public function store(array $data, callable $storing = null): mixed {
            if (CustomerModel::where('national_code', $data['national_code'])->exists()) {
                throw new CustomException('this national_code is already taken', 986585);
            }
            return parent::store($data, $storing);
        }

        protected function uploadFile(UploadedFile $file, string $path = '/uploads', string $fieldName = null): string {
            if (empty($path)) {
                $path = '/uploads';
            }
            $path = "$path/" . base64_encode(Str::random(32));
            return $file->storeAs($path, $file->getClientOriginalName());
        }

        public function getByRequestAndEloquent(): array {
            return parent::getByRequestAndEloquent();
        }

        public function getById(string $id): object {
            return parent::getById($id);
        }

        public function updateById(array $updateData, callable $updating = null): bool|int {
            if (is_null($updating)) {
                $updating = function ($eloquent, &$updateData) {
                    $customer = $this->getFirstByEloquent($eloquent);
                    if (CustomerModel::where('id', '!=', $customer->id)->where('national_code', $updateData['national_code'])->exists()) {
                        throw new CustomException('this meli_code is already taken', 986585);
                    }
                    if (array_key_exists('file', $updateData) && is_file($customer->file)) {
                        unlink($customer->file);
                    }
                };
            }
            return parent::update($updateData, $updating);
        }

        public function deleteById(string $id, callable $deleting = null): mixed {
            return parent::deleteById($id, $deleting);
        }
    }

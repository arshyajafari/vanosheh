<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\ReturnProductModel;

    class ReturnProductAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(ReturnProductModel::class)
                ->setResource(ReturnProductResource::class)
                ->setValidationRules([
                    'store' => [
                        'customer_id' => ['required', 'integer', 'exists:customers,id'],
                        'product_id' => ['required', 'integer', 'exists:products,id'],
                        'member_id' => ['required', 'integer', 'exists:members,id'],
                        'stock' => ['required', 'integer', 'between:0,10000'],
                        'description' => ['nullable', 'string', 'max:1500'],
                        'return_status' => ['nullable', 'string', 'max:50'],
                    ],
                    'update' => [
                        'customer_id' => ['integer', 'exists:customers,id'],
                        'product_id' => ['integer', 'exists:products,id'],
                        'member_id' => ['integer', 'exists:members,id'],
                        'stock' => ['integer', 'between:0,10000'],
                        'description' => ['string', 'max:1500'],
                        'return_status' => ['string', 'max:50'],
                    ],
                    'getQuery' => [
                        'search' => ['string', 'max:300'],
                    ]
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where(function ($q) use ($query) {
                            $q
                                ->where('full_name', 'LIKE', "%{$query['search']}%")
                                ->orWhere('title', 'LIKE', "%{$query['search']}%");
                        });
                    },
                ]);
            parent::__construct();
        }
    }

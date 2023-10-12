<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\ProductModel;

    class ProductAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(ProductModel::class)
                ->setResource(ProductResource::class)
                ->setValidationRules([
                    'store' => [
                        'title' => ['required', 'string', 'max:300'],
                        'picture' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'stock' => ['required', 'integer', 'between:0,10000'],
                        'expiration_date' => ['nullable', 'date_format:Y-m-d'],
                        'description' => ['nullable', 'string', 'max:1500'],
                        'price' => ['required', 'integer', 'between:0,10000000000'],
                        'discount' => ['required', 'integer', 'between:0,10000000000'],
                        'gift_product' => ['nullable', 'string', 'max:10'],
                    ],
                    'update' => [
                        'title' => ['string', 'max:300'],
                        'picture' => ['file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                        'stock' => ['integer', 'between:0,10000'],
                        'expiration_date' => ['date_format:Y-m-d'],
                        'description' => ['string', 'max:1500'],
                        'price' => ['integer', 'between:0,10000000000'],
                        'discount' => ['integer', 'between:0,10000000000'],
                        'gift_product' => ['string', 'max:10'],
                    ],
                    'getQuery' => [
                        'search' => ['string', 'max:300'],
                    ]
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                    },
                ]);
            parent::__construct();
        }
    }

<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\OrderProductModel;

    class OrderProductAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(OrderProductModel::class)
                ->setResource(OrderProductResource::class)
                ->setValidationRules([
                    'store' => [
                        'order_id' => ['required', 'integer', 'exists:orders,id'],
                        'product_id' => ['required', 'integer', 'exists:products,id'],
                        'quantity' => ['required', 'integer', 'between:0,10000'],
                        'gift_quantity' => ['required', 'integer', 'between:0,10000'],
                        'price' => ['required', 'integer', 'between:0,10000000000'],
                        'total_invoice' => ['required', 'integer', 'between:0,10000000000'],
                    ],
                    'update' => [
                        'order_id' => ['integer', 'exists:orders,id'],
                        'product_id' => ['integer', 'exists:products,id'],
                        'quantity' => ['integer', 'between:0,10000'],
                        'gift_quantity' => ['integer', 'between:0,10000'],
                        'price' => ['integer', 'between:0,10000000000'],
                        'total_invoice' => ['integer', 'between:0,10000000000'],
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

<?php

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\CustomerSettlementModel;

    class CustomerSettlementAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(CustomerSettlementModel::class)
                ->setResource(CustomerSettlementResource::class)
                ->setValidationRules([
                    'store' => [
                        'customer_id' => ['required', 'integer', 'exists:customers,id'],
                        'member_id' => ['required', 'integer', 'exists:members,id'],
                        'payment_type' => ['required', 'string', 'max:50'],
                        'bank_title' => ['nullable', 'string', 'max:150'],
                        'account_number' => ['nullable', 'string', 'max:50'],
                        'due_date' => ['nullable', 'date_format:Y-m-d'],
                        'cheque_number' => ['nullable', 'string', 'max:50'],
                        'cheque_status' => ['nullable', 'string', 'max:50'],
                        'submit_number' => ['nullable', 'string', 'max:50'],
                        'received_amount' => ['nullable', 'integer', 'max:10000000000'],
                        'discount' => ['nullable', 'integer', 'max:3'],
                        'amount' => ['required', 'integer', 'max:10000000000'],
                        'description' => ['nullable', 'string', 'max:1500'],
                    ],
                    'update' => [
                        'customer_id' => ['integer', 'exists:customers,id'],
                        'member_id' => ['integer', 'exists:members,id'],
                        'payment_type' => ['string', 'max:50'],
                        'bank_title' => ['string', 'max:150'],
                        'account_number' => ['string', 'max:50'],
                        'due_date' => ['date_format:Y-m-d'],
                        'cheque_number' => ['string', 'max:50'],
                        'cheque_status' => ['string', 'max:50'],
                        'submit_number' => ['string', 'max:50'],
                        'received_amount' => ['integer', 'max:10000000000'],
                        'discount' => ['integer', 'max:3'],
                        'amount' => ['integer', 'max:10000000000'],
                        'description' => ['string', 'max:1500'],
                    ],
                    'getQuery' => [
                        'search' => ['string', 'max:255'],
                        'payment_type' => ['string', 'max:50'],
                        'bank_title' => ['string', 'max:50'],
                        'cheque_status' => ['string', 'max:50'],
                    ]
                ])
                ->setCasts([
                    'due_date' => ['jalali_to_gregorian:Y-m-d'],
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where(function ($q) use ($query) {
                            $q
                                ->where('cheque_number', 'LIKE', "%{$query['search']}%")
                                ->orWhere('submit_number', 'LIKE', "%{$query['search']}%");
                        });
                    },
                    'payment_type' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('payment_type', $query['payment_type']);
                    },
                    'bank_title' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('bank_title', $query['bank_title']);
                    },
                    'cheque_status' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('cheque_status', $query['cheque_status']);
                    },
                ]);
            parent::__construct();
        }
    }

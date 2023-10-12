<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\FinancialReportModel;

    class FinancialReportAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(FinancialReportModel::class)
                ->setResource(FinancialReportResource::class)
                ->setValidationRules([
                    'getQuery' => [
                        'customer_id' => ['string', 'max:300'],
                    ]
                ])
                ->setQueryToEloquentClosures([
                    'customer_id' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('customer_id', $query['customer_id']);
                    },
                ]);
            parent::__construct();
        }
    }

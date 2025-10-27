<?php

namespace App\Datatables;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Constants\MalfunctionType;
use Illuminate\Support\Facades\Log;
use App\Traits\DataTableActionsTrait;

class CarRescueDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'identifier',
            'driver',
            'passenger',
            'malfunction_type',
            'breakdown_point',
            'status',
            'created_at',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->show(route('trips.show', $model->id))
                        ->makeLabelledIcons();
                })
                ->addColumn('identifier', function ($model) {
                    return '#' . $model->identifier;
                })
                ->addColumn('driver', function ($model) {
                    return $model->driver && $model->driver->user
                        ? $this->thumbnailTitleMeta(
                            $model->driver->avatar_url,
                            $model->driver->fullname,
                            $model->driver->phone ?? ''
                        )
                        : '-';
                })
                ->addColumn('passenger', function ($model) {
                    $passenger = $model->clients()->first()?->client;
                    return $passenger
                        ? $this->thumbnailTitleMeta(
                            $passenger->avatar_url,
                            $passenger->fullname,
                            $passenger->phone
                        )
                        : '-';
                })
                ->addColumn('malfunction_type', function ($model) {
                    $details = $model->detailable;
                    if($details) {
                        return $this->badge(MalfunctionType::get_name($details->malfunction_type), MalfunctionType::get_color($details->malfunction_type));
                    }else{
                        return '-';
                    }
                })
                ->addColumn('breakdown_point', function ($model) {
                    $details = $model->detailable;
                    if ($details) {
                        $point = $this->link($details->breakdownPoint->url, $details->breakdownPoint->name);
                        $time = $details->delivery_time->format('Y-m-d H:i');
                        return '<small><strong>' . $point . '</strong><br>' . $time . '</small>';
                    }
                    return '-';
                })
                ->addColumn('status', function ($model) {
                    $statusName = TripStatus::get_name($model->status);
                    $color = TripStatus::get_color($model->status);
                    return $this->badge($statusName, $color);
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Trip::with(['driver.user', 'clients.client', 'detailable'])
            ->where('type', TripType::CAR_RESCUE);

        // Filter by status
        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        // Filter by driver
        if ($request->driver_filter) {
            $query->where('driver_id', $request->driver_filter);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by malfunction type - Use whereHasMorph to properly filter polymorphic relationships
        if ($request->malfunction_type_filter) {
            $query->whereHasMorph('detailable', [\App\Models\CarRescueDetail::class], function ($q) use ($request) {
                $q->where('malfunction_type', $request->malfunction_type_filter);
            });
        }

        return $query->latest()->get();
    }
}

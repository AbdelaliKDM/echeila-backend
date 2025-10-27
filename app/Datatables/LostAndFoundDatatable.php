<?php

namespace App\Datatables;

use Exception;
use Illuminate\Support\Str;
use App\Models\LostAndFound;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Constants\LostAndFoundStatus;
use App\Traits\DataTableActionsTrait;

class LostAndFoundDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'item',
            'passenger',
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
                        ->edit(route('lost-and-founds.edit', $model->id))
                        ->modalButton('mark-as-returned-modal', __('app.mark_as_returned'), 'bx bx-check-circle', ['id' => $model->id], $model->status === LostAndFoundStatus::FOUND, LostAndFoundStatus::get_color(LostAndFoundStatus::RETURNED))
                        ->delete($model->id)
                        ->makeLabelledIcons();
                })
                ->addColumn('item', function ($model) {
                    $imageUrl = $model->getFirstMediaUrl(LostAndFound::IMAGE) ?: asset('assets/img/default-item.png');
                    $description = Str::limit($model->description, 50);
                    return $this->thumbnailTitleMeta($imageUrl, $description, '');
                })
                ->addColumn('passenger', function ($model) {
                    return $model->passenger ? $model->passenger->fullname : '-';
                })
                ->addColumn('status', function ($model) {
                    return $this->badge(
                        LostAndFoundStatus::get_name($model->status),
                        LostAndFoundStatus::get_color($model->status)
                    );
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this).' Error '.$e->getMessage());
        }
    }

    public function query($request)
    {
        $query = LostAndFound::with('passenger');

        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        if ($request->passenger_filter) {
            $query->where('passenger_id', $request->passenger_filter);
        }

        return $query->latest()->get();
    }
}
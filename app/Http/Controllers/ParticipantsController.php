<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\BreadDataUpdated;


class ParticipantsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{


    public function update(Request $request, $id)
    {

        $slug = $this->getSlug($request);
        error_log($slug);


        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        $contestId = Participant::where('id' , $id)->first()['contest_id'];
        $userId = Participant::where('id' , $id)->first()['user_id'];
        $user = User::where('id', $userId)->first();

        $contestValue = Contest::where('id' , $contestId)->first()['prize'];

        $isIncrease = Participant::where('id' , $id)->first()['is_winner'];
        if($isIncrease){
            User::where('id', $userId)->update([
                'points' => $user->points + $contestValue,
            ]);
        }else{
            User::where('id', $userId)->update([
                'points' => $user->points - $contestValue,
            ]);
        }

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

}

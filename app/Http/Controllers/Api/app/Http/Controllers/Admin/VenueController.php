<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserLoginPassword;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueCategory;
use App\Models\VenueFeatureAdsPackage;
use App\Models\VenuePricing;
use App\Models\VenuesPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VenueController extends Controller
{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {

    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()
    {

        return view('admin.venue_provider.add');

    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)
    {

        //

        $validator = $request->validate([

            'name' => 'required',

            'email' => 'required|unique:users,email|email',

            'phone' => 'required',

        ]);

        $data = $request->only(['name', 'email', 'role', 'phone']);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/'), $filename);
            $data['image'] = 'public/images/' . $filename;
        }
        else{
            $data['image'] = 'public/images/1695640800.png';
        }

        $data['role'] = 'venue_provider';

        $password = random_int(10000000, 99999999);

        $data['password'] = Hash::make($password);

        // dd($message);

        $user = User::create($data);

        $message['email'] = $request->email;

        $message['password'] = $password;

        try {

            Mail::to($request->email)->send(new UserLoginPassword($message));

            return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Venue Provider Created sucessfully']);

        } catch (\Throwable $th) {

            return back()

                ->with(['status' => false, 'message' => 'Unexpected error occured']);

        }

    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($user_id)
    {

        // dd($user_id,$venue_id);

        //  Showing Entertainer Talent

        //  dd('ssa');

        $data['venue'] = Venue::with('venueFeatureAdsPackage')->with('venueCategory')->where('user_id', $user_id)->latest()->get();

        //  dd($data);

        $data['user_id'] = $user_id;

        return view('admin.venue_provider.venues.index', compact('data'));

    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($user_id)
    {

        $venue = User::find($user_id);

        return view('admin.venue_provider.edit', compact('venue'));

    }

    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $user_id)
    {

        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

            'phone' => 'required',

        ]);

        $recruiter = User::find($user_id);

        $recruiter->name = $request->input('name');

        $recruiter->email = $request->input('email');

        $recruiter->phone = $request->input('phone');
        if ($request->hasFile('image')) {
            $destination = 'public/images/' . $recruiter->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/images/', $filename);
            $image = 'public/images/' . $filename;
            $recruiter->image = $image;
        }
        $recruiter->update();

        return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Venue Provider Updated sucessfully']);
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($user_id)
    {

        User::destroy($user_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Venue Provider Deleted sucessfully']);

    }

    public function createVenueIndex($user_id)
    {

        $data['user_id'] = $user_id;

        $data['venue_categories'] = VenueCategory::select('id', 'category')->get();

        $data['venue_feature_ads_packages'] = VenueFeatureAdsPackage::select('id', 'title', 'price', 'validity')->get();

        return view('admin.venue_provider.venues.add', compact('data'));

    }

    public function storeVenue(Request $request, $user_id)
    {
        ($request->photos);
        if ($request->has('venue_feature_ads_packages_id')) {

            $validator = $request->validate([

                'title' => 'required',

                'category_id' => 'required',

                'description' => 'required',

                'seats' => 'required',

                'stands' => 'required',

                'photos' => 'required',

                'opening_time' => 'required',

                'closing_time' => 'required',
                'address' => 'required',

                'venue_feature_ads_packages_id' => 'required',

            ]);

            // return $request;

            $data = $request->only(['title', 'user_id', 'category_id', 'description', 'seats', 'stands', 'opening_time', 'closing_time', 'venue_feature_ads_packages_id' , 'address' , 'latitude' , 'longitude']);

            $data['feature_status'] = 1;

            if ($request->has('amenities')) {

                $data['amenities'] = implode(',', $request->amenities);

            }

            $data['user_id'] = $user_id;

            $user = Venue::create($data);

            // if ($request->file('photos')) {

            //     foreach ($request->file('photos') as $data) {

            //         $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());

            //         $data->move('public/admin/assets/img/venue', $image);

            //         VenuesPhoto::create([

            //             'photos' => '' . $image,

            //             'venue_id' => $user->id,

            //         ]);

            //     }

            // }

            if ($request->file('photos')) {
                foreach ($request->file('photos') as $data) {
                    $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                    $data->move(public_path('images'), $image);
                    VenuesPhoto::create([
                        'photos' =>  'public/images/' . $image,
                        'venue_id' => $user->id
                    ]);
                }
            }

            return redirect()->route('venue.show', $user->user_id)->with(['status' => true, 'message' => 'Venue Created sucessfully']);

        } else {

            $validator = $request->validate([

                'title' => 'required',

                'category_id' => 'required',

                'description' => 'required',

                'seats' => 'required',

                'stands' => 'required',

                'photos' => 'required',

                'opening_time' => 'required',

                'closing_time' => 'required',
                'address' => 'required',

            ]);

            // dd($request->input());

            $data = $request->only(['title', 'user_id', 'category_id', 'description', 'seats', 'stands', 'opening_time', 'closing_time' , 'address' , 'latitude' , 'longitude']);

            // $data['amenities'] = implode(',', $request->amenities);

            if ($request->has('amenities')) {

                $data['amenities'] = implode(',', $request->amenities);

            }

            $data['user_id'] = $user_id;

            $user = Venue::create($data);

            // if ($request->file('photos')) {

            //     foreach ($request->file('photos') as $data) {

            //         $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());

            //         $data->move('public/admin/assets/img/venue', $image);

            //         VenuesPhoto::create([

            //             'photos' => '' . $image,

            //             'venue_id' => $user->id,

            //         ]);

            //     }

            // }

            if ($request->file('photos')) {
                foreach ($request->file('photos') as $data) {
                    $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                    $data->move(public_path('images'), $image);
                    VenuesPhoto::create([
                        'photos' =>  'public/images/' . $image,
                        'venue_id' => $user->id
                    ]);
                }
            }

            // dd('ss');

            return redirect()->route('venue.show', $user->user_id)->with(['status' => true, 'message' => 'Venue Created sucessfully']);

            // return view('admin.entertainer.Talent.add');

        }

    }

    public function destroyVenue($user_id)
    {

        $data = Venue::destroy($user_id);

        // return redirect()->route('admin.venue_provider.venues.index',$id)->with(['status'=>true, 'message' => 'Venue Deleted sucessfully']);

        return redirect()->back()->with(['status' => true, 'message' => 'Venue Deleted sucessfully']);

    }

    public function editVenue($user_id, $venue_id)
    {

        //$data['user_id'] = EntertainerDetail::find($id);

        $venue['venue'] = Venue::where('id', $venue_id)->with('venueCategory')->get();

        // $venue['venue']=Venue::find($venue_id);

        $venue['venue_categories'] = VenueCategory::select('id', 'category')->get();

        $venue['venue_feature_ads_packages'] = VenueFeatureAdsPackage::select('id', 'title', 'price', 'validity')->get();

        // dd('ss');

        $venue['user_id'] = $user_id;

        return view('admin.venue_provider.venues.edit', compact('venue'));

    }

    public function updateVenue(Request $request, $user_id)
    {

        // dd($request->input());

        if ($request->venue_feature_ads_packages_id !== null && $request->feature_ads === 'on') {

            $validator = $request->validate([

                'title' => 'required',

                'category_id' => 'required',

                'description' => 'required',

                'seats' => 'required',

                'stands' => 'required',

                'opening_time' => 'required',

                'closing_time' => 'required',
                'address' => 'required',

            ]);

            // dd($request->input());

            $venue = Venue::find($user_id);

            $venue->title = $request->input('title');

            $venue->description = $request->input('description');

            $venue->seats = $request->input('seats');

            $venue->stands = $request->input('stands');

            $venue->opening_time = $request->input('opening_time');

            $venue->closing_time = $request->input('closing_time');

            $venue->category_id = $request->input('category_id');
            $venue->address = $request->input('address');

            $venue->latitude = $request->input('latitude');

            $venue->longitude = $request->input('longitude');

            if ($request->has('amenities')) {

                $venue->amenities = implode(',', $request->amenities);

            }

            $venue->venue_feature_ads_packages_id = $request->venue_feature_ads_packages_id;

            $venue->feature_status = 1;

            $venue->update();

            return redirect()->route('venue.show', $venue->user_id)->with(['status' => true, 'message' => 'Venue Updated sucessfully']);

        } else if ($request->venue_feature_ads_packages_id === null && $request->feature_ads === 'on') {

            // dd($request->input());

            return redirect()->back()->with(['status' => false, 'message' => 'Feature Package Must Be Selected']);

        } else {

            // dd($request->all());

            $validator = $request->validate([

                'title' => 'required',

                'category_id' => 'required',

                'description' => 'required',

                'seats' => 'required',

                'stands' => 'required',

                'opening_time' => 'required',

                'closing_time' => 'required',
                'address' => 'required',

            ]);

            // dd($request->input());

            $venue = Venue::find($user_id);

            $venue->title = $request->input('title');

            $venue->category_id = $request->input('category_id');

            $venue->description = $request->input('description');

            $venue->seats = $request->input('seats');

            $venue->stands = $request->input('stands');

            $venue->opening_time = $request->input('opening_time');

            $venue->closing_time = $request->input('closing_time');
            $venue->address = $request->input('address');

            $venue->latitude = $request->input('latitude');

            $venue->longitude = $request->input('longitude');

            if ($request->has('amenities')) {

                $venue->amenities = implode(',', $request->amenities);

            }

            $venue->venue_feature_ads_packages_id = null;

            $venue->feature_status = 0;

            $venue->update();

            return redirect()->route('venue.show', $venue->user_id)->with(['status' => true, 'message' => 'Venue Updated sucessfully']);

        }

    }

    public function venueCategoriesIndex()
    {

        $data = VenueCategory::select('id', 'category')->latest()->get();

        return view('admin.Categories.Venue.index', compact('data'));

    }

    public function venueCategoryStore(Request $request)
    {

        $validator = $request->validate([

            'category' => 'required',

        ]);

        $data = $request->only(['category']);

        $data = VenueCategory::create($data);

        return redirect()->route('venue-providers.venue.categories.index')->with(['status' => true, 'message' => 'Venue Category Created sucessfully']);

    }

    public function venueCategoryEditIndex($category_id)
    {

        $data = VenueCategory::select('id', 'category')->where('id', $category_id)->first();

        return view('admin.Categories.Venue.edit', compact('data'));

    }

    public function updateVenueCategory(Request $request, $category_id)
    {

        $validator = $request->validate([

            'category' => 'required',

        ]);

        $venue_category = VenueCategory::find($category_id);

        $venue_category->category = $request->category;

        $venue_category->update();

        return redirect()->route('venue-providers.venue.categories.index')->with(['status' => true, 'message' => 'Venue Category Updated sucessfully']);

    }

    public function destroyVenueCategory($category_id)
    {

        VenueCategory::destroy($category_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Category Deleted sucessfully']);

    }

    //Photos

    public function showPhoto($user_id, $venue_id)
    {

        //  Showing Entertainer Talent

        $data['photos'] = VenuesPhoto::where('venue_id', $venue_id)->latest()->get();

        // dd($data['user_id']);

        $data['user_id'] = $user_id;

        $data['venue_id'] = $venue_id;

        return view('admin.venue_provider.venues.photo.index', compact('data'));

    }

    //Photo

    public function destroyPhoto($photo_id)
    {

        VenuesPhoto::destroy($photo_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Photo Deleted sucessfully']);

    }

    public function editPhoto($user_id, $venue_id, $photo_id)
    {

        //$data['user_id'] = EntertainerDetail::find($id);

        $photo['user_id'] = $user_id;

        $photo['venue_id'] = $venue_id;

        $photo['photo_id'] = $photo_id;

        $photo['photo'] = VenuesPhoto::find($photo_id);

        //dd( $photo['user_id']);

        return view('admin.venue_provider.venues.photo.edit', compact('photo'));

    }

    public function updatePhoto(Request $request, $user_id, $venue_id, $photo_id)
    {

        $validator = $request->validate([

            'photos' => 'required',

            // 'description' => 'required',

            // 'images'=>'required',

        ]);

        $photo = VenuesPhoto::find($photo_id);

        if ($request->hasfile('photos')) {

            $file = $request->file('photos');

            $extension = $file->getClientOriginalExtension(); // getting image extension

            $filename = time() . '.' . $extension;

            $file->move(public_path('admin/assets/img/venue/'), $filename);

            $photo->photos = '' . $filename;

        }

        $photo->update();

        return redirect()->route('venue-providers.venue.photo.show', ['user_id' => $user_id, 'venue_id' => $venue_id, 'photo_id' => $photo_id])->with(['status' => true, 'message' => 'Photo Updated sucessfully']);

    }

    public function pricePackagesIndex($user_id, $venue_id)
    {

        $data['price_packages'] = VenuePricing::where('venues_id', $venue_id)->latest()->get();

        $data['venue_id'] = $venue_id;

        $data['user_id'] = $user_id;

        // dd($data['price_packages']);

        return view('admin.venue_provider.venues.Price_packages.index', compact('data'));

    }

    public function createPricePackageIndex($user_id, $venue_id)
    {

        // return dd($venue_id);

        $data['venue_id'] = $venue_id;

        $data['user_id'] = $user_id;

        return view('admin.venue_provider.venues.Price_packages.add', compact('data'));

    }

    public function storePricePackage(Request $request, $user_id, $venue_id)
    {

        $validator = $request->validate([

            'price' => 'required',

            'day' => 'required',

            'opening_time' => 'required',

            'closing_time' => 'required',

        ]);

        $data = $request->only(['price', 'day', 'opening_time', 'closing_time']);

        $data['venues_id'] = $venue_id;

        $user = VenuePricing::create($data);

        return redirect()->route('venue-providers.venue.venue_pricings.index', ['user_id' => $venue_id, 'venue_id' => $venue_id])->with(['status' => true, 'message' => 'Price Package Created Sucessfully']);

    }

    public function editPricePackageIndex($user_id, $venue_pricing_id)
    {

        $data['price_package'] = VenuePricing::where('id', $venue_pricing_id)->first();

        $data['user_id'] = $user_id;

        return view('admin.venue_provider.venues.Price_packages.edit', compact('data'));

    }

    public function updatePricePackage(Request $request, $user_id, $venue_pricing_id)
    {

        $validator = $request->validate([

            'price' => 'required',

            'day' => 'required',

            'opening_time' => 'required',

            'closing_time' => 'required',

        ]);

        // dd($request->time);

        $price_package = VenuePricing::find($venue_pricing_id);

        $price_package->price = $request->input('price');

        $price_package->day = $request->input('day');

        $price_package->day = $request->input('opening_time');

        $price_package->day = $request->input('closing_time');

        $price_package->update();

        return redirect()->route('venue-providers.venue.venue_pricings.index', ['user_id' => $user_id, 'venue_id' => $price_package['venues_id']])->with(['status' => true, 'message' => 'Price Package Updated Sucessfully']);

    }

    public function destroyPricePackage($venue_pricing_id)
    {

        VenuePricing::where('id', $venue_pricing_id)->delete();

        return redirect()->back()->with(['status' => true, 'message' => 'Price Package Deleted Sucessfully']);

    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\Country;
use App\Repository\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->repo = $countryRepository;
    }

    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {

        $params = $this->validate($request, [
            'fields' => 'string|filled',
            'include' => 'string|filled',
            'sort' => 'string',
            'limit' => 'integer',
        ]);

        $countries = $this->repo->getAll($params);

        return Country::collection($countries);
    }


    public function show(Request $request, $id)
    {

        $params = $this->validate($request, [
            'fields' => 'string|filled',
            'include' => 'string|filled',
        ]);

        $country = $this->repo->findCountry($id, $params);

        return new Country($country);
    }
}

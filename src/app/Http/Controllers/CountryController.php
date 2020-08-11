<?php

namespace App\Http\Controllers;

use App\Http\Resources\Country;
use App\Repository\CountryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * CountryController
 */
class CountryController extends Controller
{
    /**
     * Create a new controller instance.
     * Inject countryRepository in controller
     *
     * @return void
     */
    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->repo = $countryRepository;
    }

    /**
     *  index: Returns all country from database with wikipedia and youtube data
     *
     * @param  Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ?ResourceCollection
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


    /**
     * show : Returns single country from database with populated data from wikipedia and youtube
     *
     * @param  Request $request
     * @param  int $id
     * @return Country
     */
    public function show(Request $request, int $id): Country
    {

        $params = $this->validate($request, [
            'fields' => 'string|filled',
            'include' => 'string|filled',
        ]);

        $country = $this->repo->findCountry($id, $params);

        return new Country($country);
    }
}

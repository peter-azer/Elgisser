<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use App\Models\Subject;
use App\Models\Style;
use App\Models\Medium;
use App\Models\Material;
class FilterController extends Controller
{
    /**
     * Display the filter options for artworks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::all();
        $styles = Style::all();
        $mediums = Medium::all();
        $materials = Material::all();

        return response()->json([
            'subjects' => $subjects,
            'styles' => $styles,
            'mediums' => $mediums,
            'materials' => $materials,
        ]);
    }
}

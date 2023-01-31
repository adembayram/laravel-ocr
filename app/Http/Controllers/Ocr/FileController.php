<?php

namespace App\Http\Controllers\Ocr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use PhpOffice\PhpWord\IOFactory;
use App\Http\Requests\Ocr\OcrCreateRequest;
use Imagick;
use App\Models\Ocr;
use App\Models\OcrDetail;


class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

         $ocrs = Ocr::OrderBy('id','DESC')->get();

        return view('index',compact('ocrs'));

        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OcrCreateRequest $request)
    {
        //
        $fileName = $request->fileInput->getClientOriginalName() . "_" . date("YmdHis") . "." . $request->fileInput->getClientOriginalextension();
        $fileNameWithUpload = 'uploads/ocr/' . $fileName;
        $request->fileInput->move(public_path('uploads/ocr'), $fileName);


         $ocr = Ocr::create([

            'filename' =>  $fileName,
            'old_filename' => $request->fileInput->getClientOriginalName()

         ]);

        $content = ''; 

        if($request->fileInput->getClientOriginalextension() == "pdf"){
          

            $imagick = new \Imagick();
            $imagick->readImage(public_path($fileNameWithUpload));

            foreach ($imagick as $i => $image) {
                $image->setImageFormat('png');
                $filename = "pdf-image/".date("YmdHis").'-' . $i . '.png';
                $image->writeImage($filename);            
                
                $content = $this->text_tesseract($filename);         
           
                $ocrDetail = OcrDetail::create([

                    'ocr_id' => $ocr->id,
                    'content' => $content

                ]);
                

            }




        }elseif($request->fileInput->getClientOriginalextension() == "docx"){
            
            $phpWord = IOFactory::load(public_path($fileNameWithUpload));

            $page = 1;
            foreach($phpWord->getSections() as $section) {
                            //$content = 'Sayfa ' . $page . ': ';
                            foreach($section->getElements() as $element) {
                                if (method_exists($element, 'getElements')) {
                                    foreach($element->getElements() as $childElement) {
                                        if (method_exists($childElement, 'getText')) {
                                            $content .= $childElement->getText() . ' ';
                                        }
                                        else if (method_exists($childElement, 'getContent')) {
                                            $content .= $childElement->getContent() . ' ';
                                        }
                                    }
                                }
                                else if (method_exists($element, 'getText')) {
                                    $content .= $element->getText() . ' ';
                                }
                            }
                           // echo $content . PHP_EOL;
                            $page++;
                        }
            
                        $ocrDetail = OcrDetail::create([

                            'ocr_id' => $ocr->id,
                            'content' => $content
        
                        ]);
   

        }else{

            $content = $this->text_tesseract($fileNameWithUpload);

            $ocrDetail = OcrDetail::create([

                'ocr_id' => $ocr->id,
                'content' => $content

            ]);

        }


        return redirect()->route('index')->withSuccess('Operation Success!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $ocr = OcrDetail::where('ocr_id',$id)->get();

        return response()->json($ocr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

      

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function text_tesseract($path){

        return (new TesseractOCR(public_path($path)))->run(); 

    
    }

}
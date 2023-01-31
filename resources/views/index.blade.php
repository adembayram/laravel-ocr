<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('plugins/')}}/toastr/toastr.min.css">
    <title>OCR Project</title>
    <style>
        .file-upload input[type='file'] {
  display: none;
}

body {
  background: #00B4DB;
  background: -webkit-linear-gradient(to right, #0083B0, #00B4DB);
  background: linear-gradient(to right, #0083B0, #00B4DB);
  height: 100vh;
}

.rounded-lg {
  border-radius: 1rem;
}

.custom-file-label.rounded-pill {
  border-radius: 50rem;
}

.custom-file-label.rounded-pill::after {
  border-radius: 0 50rem 50rem 0;
}

    </style>
</head>
<body>
    <section>
        <div class="container p-5">
         
          <div class="row mb-5 text-center text-white">
            <div class="col-lg-10 mx-auto">
              <h1 class="display-4">File upload Ocr </h1>
              
            </div>
          </div>         
      
          <div class="row">
            <div class="col-lg-12 mx-auto">
              <div class="p-5 bg-white shadow rounded-lg">          
      
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                      <form method="POST" action="{{ route('index') }}" enctype="multipart/form-data">
                            @CSRF
                        <div class="form-group">
                          <label for="fileInput">Dosya Seç</label>
                          <input type="file" class="form-control-file" required id="fileInput" name="fileInput">
                          <small class="form-text text-muted">Sadece jpeg, png ve pdf dosyalarını yükleyebilirsiniz.</small>
                        </div>
                        <div class="form-group">
                          <label for="fileName">Dosya Adı</label>
                          <input type="text" class="form-control" id="fileName" name="fileName" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Yükle</button>
                      </form>
                    </div>
                  </div>
      
              </div>
            </div>
          </div>


          <div class="row mt-2">
            <div class="col-lg-12 mx-auto">
              <div class="p-5 bg-white shadow rounded-lg">          
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Created Date</th>
                      <th scope="col">File Name</th>
                      <th scope="col">Content</th>

                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($ocrs as $ocr)                     
                   
                    <tr>
                      <th scope="row">{{ $loop->iteration	 }}</th>
                      <td>{{ $ocr->created_at }}</td>
                      <td>{{ $ocr->old_filename }}</td>
                      <td><button class="btn btn-sm btn-success content-view" data-route="{{ route('api.ocrDetails',$ocr->id) }}" data-value="{{ $ocr->id }}">View</button></td>
                      
                    </tr>
                    
                    @endforeach
                  </tbody>
                </table>
              
      
              </div>
            </div>
          </div>


        </div>
      </section>



      <div class="modal fade" id="contentDetailModal" tabindex="-1" role="dialog" aria-labelledby="contentDetailModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="contentDetailModalTitle"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="col-lg-12" style="width: 100%">
                <table id='table' class="table table-responsive table-hover col-12">
                
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Content</th>                    
                  </tr>
                  </thead>
                  <tbody id="table-tbody">
  
                  </tbody>
                </table>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>             
            </div>
          </div>
        </div>
      </div>
      

      <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
  
      <script src="{{asset('plugins/')}}/toastr/toastr.min.js"></script>

      @if($errors->any())
      
        
        <script>
          $(document).ready(function(){
            @foreach($errors->all() as $error)
              toastr.error("{{$error}}")
            @endforeach
          });
        </script>
        
      
      @endif
      
      @if(session('success'))
      
      <script>
        $(document).ready(function(){
          toastr.success("{{ session('success') }}")
        });
      </script>
      
      @endif

     

      <script>
        document.getElementById("fileInput").addEventListener("change", function() {
  document.getElementById("fileName").value = this.files[0].name;
});

      $(document).ready(function(){

        $('.content-view').click(function(e){

          e.preventDefault();
          let ocrURL = $(this).data('route');         
          
          $.ajax({

            url: ocrURL,
            type: 'GET',
            dataType: 'json',
            success:function(data){
              let table = '';

              $.each(data, function (key, value) {  
               
                table += '<tr>';
                  table += '<td>' + 
                    value.id + '</td>';

                    table += '<td>' + 
                    value.content + '</td>';                

                    table += '</tr>';
              });
              $('#table-tbody').empty();
              $('#table-tbody').append(table);
              $('#contentDetailModal').modal('show');
            }

          })


        })


      });

      </script>
</body>
</html>



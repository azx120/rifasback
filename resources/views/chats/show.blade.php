@extends('layouts.app')

@section('content')
<style>

.messages {
  height: auto;
  min-height: calc(100% - 93px);
  max-height: calc(100% - 93px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  .messages {
    max-height: calc(100% - 105px);
  }
}
.messages::-webkit-scrollbar {
  width: 8px;
  background: transparent;
}
.messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.3);
}
.messages ul li {
  display: inline-block;
  clear: both;
  float: left;
  margin: 15px 15px 5px 15px;
  width: calc(100% - 25px);
  font-size: 0.9em;
}
.messages ul li:nth-last-child(1) {
  margin-bottom: 20px;
}
.messages ul li.sent img {
  margin: 6px 8px 0 0;
}
.messages ul li.sent p {
  background: #435f7a;
  color: #f5f5f5;
}
.messages ul li.replies img {
  float: right;
  margin: 6px 0 0 8px;
}
.messages ul li.replies p {
  background: #f5f5f5;
  float: right;
}
.messages ul li img {
  width: 30px;
  border-radius: 50%;
  float: left;
}
.messages ul li p {
  display: inline-block;
  padding: 10px 15px;
  border-radius: 20px;
  max-width: 205px;
  line-height: 130%;
}
.scrolling {
  height: 20em;
  line-height: 1em;
  overflow-x: hidden;
  overflow-y: scroll;
  width: 100%;
}

.scrolling::-webkit-scrollbar {
    width: 8px;     /* Tamaño del scroll en vertical */
    height: 8px;    /* Tamaño del scroll en horizontal */
   
}

.scrolling::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
}

/* Cambiamos el fondo y agregamos una sombra cuando esté en hover */
.scrolling::-webkit-scrollbar-thumb:hover {
    background: #b3b3b3;
    box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
}

/* Cambiamos el fondo cuando esté en active */
.scrolling::-webkit-scrollbar-thumb:active {
    background-color: #999999;
}

@media screen and (min-width: 735px) {
  .messages ul li p {
    max-width: 300px;
  }
}

</style>
<div class="pagetitle mb-1 mt-1">
  <h1>Chats</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Chats</li>
      
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <div class="form-check form-switch mt-3">
          <input class="form-check-input" type="checkbox" id="CheckIA" @if($datas->ia == 1) checked @endif>
          <label class="form-check-label" for="CheckIA">Automatico por la IA</label>
        </div>
          <div class="messages scrolling mt-2">
            <ul id="chatList">
              @foreach ($datas->chat as $item)
                @if ($item->type == 'ASK')
                  <li class=" sent">
                    <img src="{{ asset('img/usuarios.png') }}" alt="" />
                    <p>{{$item->msg}}</p>
                  </li>
                @else 
                  <li class=" replies">
                    <img src="{{ asset('img/robot.jpg') }}" alt="" />
                    <p>{{$item->msg}}</p>
                  </li>
                @endif  
              @endforeach
            </ul>
          </div>          
          <div class="input-group pt-3 pt-3">
          <input type="text" class="form-control" rows="10" cols="50" placeholder="Recipient's username" id="msgToSend">
            <div class="input-group-append">
              <button type="button" class="btn btn-info ml-2" id="sendMsg"><i class="bi bi-send"></i></button>
            </div>
          </div>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>
<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>

<script>
  $(document).ready(function(){
    $(".scrolling").animate({ scrollTop: 9999999 }, 0);

    $("#CheckIA").on('change', function(){
      let categoryId = this.value;
      let baseUrl = '{{ url("chatsUser") }}/active-ia';
      $.ajax({
          type: "POST",
          data: {"_token": "{{ csrf_token() }}", chatId: "{{$datas->id}}", value: "{{$datas->ia}}"},
          url: baseUrl,
          success: function(response)
          {
            $("#CheckIA").prop("checked", response);
            
          }
      });
    });

    $("#sendMsg").on('click', function(){
      let msgToSend = $("#msgToSend").val();
      let baseUrl = '{{ url("api/postChat") }}';
      if(msgToSend !== ""){
        $.ajax({
          type: "POST",
          data: {
            "_token": "{{ csrf_token() }}", 
            phone: "{{$datas->phone}}", 
            msg: msgToSend,
          },
          url: baseUrl,
          success: function(response)
          {
            if(response.status == 200){
              $("#CheckIA").prop("checked", response.check);
              $("#msgToSend").val('');
              $("#chatList").append(`<li class=" replies"><img src="{{ asset('img/robot.jpg') }}" alt="" /><p>${msgToSend}</p></li>`);
              $(".scrolling").animate({ scrollTop: 9999999 }, 0);


            }
            
          }
        });
      }
      
      
    });


  });
</script>
@endsection

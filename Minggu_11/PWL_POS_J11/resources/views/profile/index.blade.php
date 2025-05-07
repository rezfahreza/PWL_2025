@extends('layouts.template')
 
 @section('content')
 
 <div class="card">
     <div class="card-header">
         <h3 class="card-title">Profile Pengguna</h3>
         <div class="card-tools">
          <button type="button" onclick="modalAction('{{ route('profile.edit_profile') }}')" class="btn btn-primary">Edit Foto Profile</button>
         </div>
     </div>
 
     <div class="card-body">
         {{-- Alert --}}
         @if(session('success'))
             <div class="alert alert-success">{{ session('success') }}</div>
         @endif
         @if(session('error'))
             <div class="alert alert-danger">{{ session('error') }}</div>
         @endif
 
         <div class="row">
            <div class="col-md-3 text-center">
                <img src="{{ asset('profile_photo/' . ($user->photo ?? 'default.png')) }}"
                class="img-circle elevation-2 shadow-lg"
                alt="User Image"
                style="width: 130px; height: 130px; object-fit: cover; border-radius: 50%;">
            </div>
             <div class="col-md-9 text-secondary">
                 <p><strong class="text-dark">ID Level:</strong> {{ $user->level_id }}</p>
                 <p><strong class="text-dark">Username:</strong> {{ $user->username }}</p>
                 <p><strong class="text-dark">Nama:</strong> {{ $user->nama }}</p>
                 <p><strong class="text-dark">Role:</strong> {{ $user->level->level_nama ?? 'Tidak diketahui' }}</p>
             </div>
         </div>
     </div>
 </div>
 
 <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
 @endsection
 
 @push('js')
 <script>
     function modalAction(url = '') {
         console.log("Fetching modal from:", url);
         $('#myModal').load(url, function(response, status, xhr) {
             if (status == "error") {
                 console.log("Error loading modal:", xhr.status, xhr.statusText);
             } else {
                 $('#myModal').modal('show');
             }
         });
     }
 </script>
 @endpush
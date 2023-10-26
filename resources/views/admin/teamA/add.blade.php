@extends('admin.layout.app')
@section('title', 'Privacy Policy Edit')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form action="{{url('teamA.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Privacy Policy</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-control">
                                        @error('name')
                                        <div class="text-danger">
                                            Please fill in the  Name
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control">
                                        @error('phone')
                                        <div class="text-danger">
                                            Please fill in the  Phone
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control">
                                        @error('email')
                                        <div class="text-danger">
                                            Please fill in the  Email
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Select</label>
                                        <select name="sect" class="form-control">
                                            <option value="Sunni">Sunni</option>
                                            <option value="Shi'a">Shi'a</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary mr-1" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection
@section('js')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'description');
    </script>
@endsection



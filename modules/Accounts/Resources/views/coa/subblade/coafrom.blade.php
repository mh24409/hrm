<div class="chart-form">

    <form id="coaAddForm" action="{{ route('account.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="addCoafrom" class="">

        </div>
    </form>


    <form id="coaEditForm" action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="editCoafrom" class="">

        </div>
    </form>



    <form id="coaDeleteForm" action="{{ route('account.destroy') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="delCoafrom" class="">

        </div>
    </form>


</div>

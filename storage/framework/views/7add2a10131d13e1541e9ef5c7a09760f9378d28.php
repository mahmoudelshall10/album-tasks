<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="<?php echo e(route('albums.index')); ?>" class="btn btn-primary">Return Back</a>
                </div>

                <div class="card-body">
                <?php if(\Session::has('success')): ?>
                    <div class="alert alert-success">
                        <ul>
                            <li><?php echo \Session::get('success'); ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(\Session::has('error')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <li><?php echo \Session::get('error'); ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if(\Session::has('error')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <li><?php echo \Session::get('error'); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-success" id="div_success">
                                <ul>Successfully Uploaded !!</ul>
                            </div>

                            <div class="alert alert-danger" id="div_error">
                                <ul>Something Wrong !!</ul>
                            </div>

                            <form action="<?php echo e(route('albums.store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="name">Album Name</label>
                                    <input type="text" required name="name" class="form-control" id="name" value="<?php echo e(old('name')); ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            <br>
                            <form action="<?php echo e(route('foot_albums.UploadMultiFile')); ?>" method="POST" class="dropzone" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="fallback">
                                    <input type="file" name="attachment" multiple id="attachment">
                                </div>
                            </form>


                            <br>
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Show</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $footer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objalbum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="remove_single_<?php echo e($objalbum->id); ?>">
                                        <td><?php echo e($objalbum->id); ?></td>
                                        <td><?php echo e($objalbum->name); ?></td>
                                        <td>
                                            <?php if($objalbum->type == 'image'): ?>
                                            <img src="<?php echo e(url('/storage/app/attachments_folder')); ?>/<?php echo e($objalbum->file_path); ?>" style="width: 60px;height: 60px;">
                                            <br>
                                            <br>
                                            <a href="<?php echo e(url('/storage/app/attachments_folder/'.$objalbum->file_path)); ?>" class="btn btn-info" download>Download</a>
                                            <?php elseif($objalbum->type == 'file'): ?>
                                            <a href="<?php echo e(route('foot_albums.ShowFile',$objalbum->id)); ?>" class="btn btn-info">Show File</a>

                                            <a href="<?php echo e(url('/storage/app/attachments_folder/'.$objalbum->file_path)); ?>" class="btn btn-info" download>Download</a>
                                            <?php endif; ?>
                                        </td>
                                        <td><button type="button" onclick="deleteSingleId(<?php echo e($objalbum->id); ?>)" class="btn btn-danger"><span aria-hidden="true">&times;</span></button></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>



                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#div_success").hide();
        $("#div_error").hide();
    });
function deleteSingleId(id){
        $.ajax({
            url:"<?php echo e(route('RemoveSingleFile')); ?>",
            type:"POST",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id},
            success:function(data){
                $("#remove_single_"+id).remove();
            }
        })
    }

    var myDropzone = new Dropzone('.dropzone', {
        url: "<?php echo e(route('foot_albums.UploadMultiFile')); ?>",
        paramName: 'attachment',
        uploadMultiple: true,
        parallelUploads: 1,
        addRemoveLinks : true,
        dictRemoveFile : "Remove",
        maxFilesize: 4,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.xlsx,.xls,.rar.rtf,.PDF,.txt,.docx,.doc",
        sending:function(file, xhr, formData) {
            formData.append('id', '');
        }
    });

    let successfulUploads = 0;

    myDropzone.on('success', function(file, response) {
        var response = JSON.parse(response);
        var url = "<?php echo e(url('')); ?>";
        $.each(response, function(index, value) {
        successfulUploads++;
        $("#myTable tbody").append(`
            <tr id="remove_single_${value.id}">
                <td>${value.id}</td>
                <td>${value.name}</td>
                <td>
                    ${value.type === 'image' ? `
                        <img src="${url+'/storage/app/attachments_folder'}/${value.file_path}" style="width: 60px; height: 60px;">
                        <br><br>
                        <a href="${url+'/storage/app/attachments_folder'}/${value.file_path}" class="btn btn-info" download>Download</a>
                    ` : value.type === 'file' ? `
                        <a href="${url+'/foot_albums/show_file'}/${value.id}" target="_blank" class="btn btn-info">Show File</a>
                        <a href="${url+'/storage/app/attachments_folder'}/${value.file_path}" class="btn btn-info" download>Download</a>
                    ` : ''}
                </td>
                <td>
                    <button type="button" onclick="deleteSingleId(${value.id})" class="btn btn-danger">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </td>
            </tr>
        `);
    });
    $("#div_success").show();
    $("#div_error").hide();
    });

    myDropzone.on('error', function(file, errorMessage) {
        $("#div_success").hide();
        $("#div_error").show();
    });

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\LaravelProject\album-task\resources\views/album/create.blade.php ENDPATH**/ ?>
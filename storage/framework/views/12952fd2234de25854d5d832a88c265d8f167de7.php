<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="<?php echo e(route('albums.index')); ?>" class="btn btn-primary">Return Back</a>
                    <br>
                    <br>
                    <p>Created By : <?php echo e($album->CreatedBy->name); ?></p>
                    <p>Album Name : <?php echo e($album->name); ?></p>
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Created By</th>
                                    <th>Name</th>
                                    <th>Show</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $album->FootAlbums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objalbum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($objalbum->id); ?></td>
                                    <td><?php echo e($objalbum->CreatedBy->name); ?></td>
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
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\LaravelProject\album-task\resources\views/album/show.blade.php ENDPATH**/ ?>
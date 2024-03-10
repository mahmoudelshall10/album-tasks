<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <a href="<?php echo e(route('albums.create')); ?>" class="btn btn-primary">Create Album</a>
                </div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(\Session::has('success')): ?>
                    <div class="alert alert-success">
                        <ul>
                            <li><?php echo \Session::get('success'); ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Show</th>
                                <th>Edit</th>
                                <th>Copy</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objalbum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td><?php echo e($objalbum->id); ?></td>
                                <td><?php echo e($objalbum->name); ?></td>
                                <td><?php echo e($objalbum->CreatedBy->name); ?></td>
                                <td><a href="<?php echo e(route('albums.show',$objalbum->id)); ?>" target="_blank" class="btn btn-success">Show</a></td>
                                <td><a href="<?php echo e(route('albums.edit',$objalbum->id)); ?>" target="_blank" class="btn btn-warning">Edit</a></td>


                                    <td><a href="#deModalCopy<?php echo e($objalbum->id); ?>" data-toggle="modal" class="btn btn-info">Copy</a>
                                    <div class="modal fade" id="deModalCopy<?php echo e($objalbum->id); ?>" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Copy Confirmation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to Copy this album?</p>
                                                    <form method="POST"  action="<?php echo e(route('albums.copy_album',$objalbum->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="form-group">
                                                            <label for="name">Album Name</label>
                                                            <input type="text" name="name" required class="form-control" id="name" value="<?php echo e($objalbum->name); ?>">
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Copy</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>


                                <td><a href="#deModal<?php echo e($objalbum->id); ?>" data-toggle="modal" class="btn btn-danger">Delete</a>
                                <div class="modal fade" id="deModal<?php echo e($objalbum->id); ?>" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this record?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST"  action="<?php echo e(route('albums.destroy',$objalbum->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('Delete'); ?>
                                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\LaravelProject\album-task\resources\views/album/index.blade.php ENDPATH**/ ?>
<div class="row">
    <!--card deck!-->
    <div class="col-12 mb-20">

        <?php 
            if ($data) { 
                $count =0;
                if($total == '1') { ?>
        <div class="row row-cols-1 row-cols-lg-1 gx-4 gy-2">
            <?php  } else { ?>
            <div class="row row-cols-1 row-cols-lg-2 gx-4 gy-2">
                <?php   }
                ?>
                @foreach ($data as $item)
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <?php 
                            if($item->is_main_branch === 'Y') { ?>
                                    <h4 class="card-title b-0 px-0 pt-0">Main Branch</h4>
                                    <?php } else { 
                                    $count++; ?>
                                    <h4 class="card-title b-0 px-0 pt-0">Branch {{ $count }}</h4>
                                    <?php } 
                if ($item->clinic_status === 'Y') { ?>
                                    <span class="badge badge-dot badge-success" title="active"></span>
                                    <?php 
               } else { ?>
                                    <span class="badge badge-dot badge-danger" title="inactive"></span>
                                    <?php } ?>
                                </div>
                                <p><span class="text-muted"><i class="fa fa-location-dot"></i>
                                    </span>&nbsp;{{ str_replace('<br>', ', ', $item->clinic_address) }}</p>
                                <p><span class="text-muted"><i class="fa fa-phone"></i>
                                    </span>&nbsp;{{ $item->clinic_phone }}</p>
                            </div>

                        </div>
                    </div>
                @endforeach
                <?php 
           } ?>
            </div>
        </div>
    </div>

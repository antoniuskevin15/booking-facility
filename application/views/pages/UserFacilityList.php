<h1 class="mb-5 login__main-title text-center">Facility Listing</h1>
<div class="container">
  <div class="row g-5">
    <?php foreach($facility as $row)
        {
            echo '<div class="col-lg-4 col-sm-6" data-aos="zoom-in">
                    <div class="facility__card">
                        <a class="facility__card-details" href="'.site_url('user/facilityDetail/'.$row['FacilityID']).'">
                            <div class="facility__card-image">
                                <img class="card-img-top " src="'.base_url().'assets/images/facility/'.$row['Image'].'" alt="facility images" title="Click for more informations about this facility">
                            </div>
                            <div class="facility__card-body">
                                <h5 class="facility__card-title text-center">'.$row['FacilityName'].'</h5>
                            </div>
                        </a>
                    </div>
            </div>';
        }
    ?>
  </div>
</div>
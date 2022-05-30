    <?php foreach($facility as $row){
        echo '
            <div class="container" data-aos="fade-right">
                <h1 class="mt-5 login__main-title text-center">'.$row['FacilityName'].'</h1>
                <div class="row fdetails__wrapper">
                    <div class="col-6 fdetails__image-wrapper">
                        <div class="fdetails__image">
                            <img class="card-img-top" src="'.base_url().'assets/images/facility/'.$row['Image'].'" alt="'. $row['FacilityName'] .'" title="'. $row['FacilityName'] .'">
                        </div>
                    </div>
                    <div class="col-6 fdetails__content">
                        <p class="fdetails__description">
                            '.$row['FacilityDetail'].'
                        </p>
                        <a href="'.site_url("user/requests/add?FID=").$row['FacilityID'].'"><button class="btn button__book">Book</button></a>
                        <a href="'.site_url('user').'"><button class="btn button__back">Back</button></a>
                    </div>
                </div>
            </div>';
    }
    ?>
    </div>
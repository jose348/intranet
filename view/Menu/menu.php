<div class="br-logo"><a href="../Home/home.php"><span><img src="../../public/img/muni.png" alt=""></span></a></div>
<div class="br-sideleft overflow-y-auto">
  <div>

  </div>
  <div class="br-sideleft-menu">


    <div class="br-menu-item br-menu-link">
      <i class="menu-item-icon fa fa-user tx-20"></i>
      <span class="menu-item-label text text-light">
        <?php echo $_SESSION["acce_rol"] ?>
      </span>
    </div><!-- menu-item -->


    <?php $variable = $_SESSION["acce_rol"];
    switch ($variable) {
      case "9":
    ?>
        <a href="#" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-sitemap tx-20"></i>
            <span class="menu-item-label ">Escalafon</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
          <li class="nav-item"><a href="../GestionEscalafon/persona.php" class="nav-link ">Calendario</a></li>
           
        </ul>

        <a href="#" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon  fa fa-money tx-20"></i>
            <span class="menu-item-label ">Boletas</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
          <li class="nav-item"><a href="../GestionBoletas/boletas.php" class="nav-link ">Boletas</a></li>
           
        </ul>


        <a href="#" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-graduation-cap tx-20"></i>
            <span class="menu-item-label ">Capacitaciones</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
          <li class="nav-item"><a href="../GestionCapacitaciones/progr_calendario.php" class="nav-link ">Calendario</a></li>
          <li class="nav-item"><a href="../GestionCapacitaciones/asistencia.php" class="nav-link ">Asistencia</a></li>
          <li class="nav-item"><a href="../GestionCapacitaciones/adc.php" class="nav-link ">Desarrollo y Capaciidades</a></li>
        </ul>




        



        <a href="#" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-plane tx-20"></i>
            <span class="menu-item-label ">Mis Vacaciones</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->



        <a href="#" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-calendar tx-20"></i>
            <span class="menu-item-label">Mis Asistencias</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->


      <?php
        break;
      default:

      ?>
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////// -->

          <a href="#" class="br-menu-link ">
            <div class="br-menu-item">
               <i class="menu-item-icon fa fa-sitemap tx-20"></i>
               <span class="menu-item-label ">Mi Escalafon</span>
           </div><!-- menu-item -->
          </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
         
          <li class="nav-item"><a href="../GestionEscalafon/persona.php" class="nav-link ">Persona</a></li>
        
        </ul>


        <a href="#" class="br-menu-link ">
            <div class="br-menu-item">
               <i class="menu-item-icon fa fa-money tx-20"></i>
               <span class="menu-item-label ">Mis Boletas</span>
           </div><!-- menu-item -->
          </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
         
          <li class="nav-item"><a href="../GestionBoletas/boletas.php" class="nav-link ">Boletas</a></li>
        
        </ul>


        <a href="../Menu/menuCapacitacion.php" class="br-menu-link ">
          <div class="br-menu-item">
            <i class="menu-item-icon fa fa-graduation-cap tx-20"></i>
            <span class="menu-item-label ">Mis Capacitaciones</span>
          </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
         
          <li class="nav-item"><a href="../GestionCapacitaciones/calendario.php" class="nav-link ">Calendario</a></li>
          <li class="nav-item"><a href="../GestionCapacitaciones/capacitaciones.php" class="nav-link ">Mis Capacitaiones</a></li>
         

        </ul>


        


    <?php
        break;
    }
    ?>





    <a href="../Logout/logout.php" class="br-menu-link">
      <div class="br-menu-item">
        <i class=" icon icon ion-power tx-20"></i>
        <span class="menu-item-label">Cerrar Session</span>
      </div><!-- menu-item -->
    </a><!-- br-menu-link -->

  </div><!-- br-sideleft-menu -->

  <br>
</div><!-- br-sideleft -->
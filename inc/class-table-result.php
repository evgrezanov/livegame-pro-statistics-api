<?php


class Livegame_Table_Result {

  public static function init(){
    add_shortcode('lgp_table_result', [__CLASS__, 'lgp_table_result']);
  }

  public static function lgp_table_result(){

    if (isset($_GET['result'])):

      ob_start();

      ?>

      <div class="row" id="lgp-result">

      <!-- тотал больше -->
      <div class="col-md-6" id="lgp_total_b">

        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">ТОТАЛ БОЛЬШЕ</span>
          <span class="badge badge-secondary badge-pill">3</span>
        </h4>

        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Handle</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>@fat</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Larry</td>
              <td>the Bird</td>
              <td>@twitter</td>
            </tr>
          </tbody>
        </table>

      </div>

      <!-- тотал меньше -->
      <div class="col-md-6" id="lgp_total_b">

        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">ТОТАЛ МЕНЬШЕ</span>
          <span class="badge badge-secondary badge-pill">3</span>
        </h4>

        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Handle</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>@fat</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Larry</td>
              <td>the Bird</td>
              <td>@twitter</td>
            </tr>
          </tbody>
        </table>

      </div>

    </div>

      <?php

      return ob_get_clean();

    endif;
  }

  public static function lgp_result_summary(){
    ob_start();
    ?>
    <table>
    <tr>
          <td>ТОТАЛ БОЛЬШЕ</td>
          <td>ТОТАЛ МЕНЬШЕ</td>
      </tr>
      <tr>
          <td>Вероятность: <?php echo round($tb_v); ?>%</td>
          <td>Вероятность: <?php echo round($tm_v); ?>%</td>
      </tr>
      <tr>
          <td>Колличестов матчей: <?php echo count($result_matches_finished_array_tb); ?></td>
          <td>Колличестов матчей: <?php echo count($result_matches_finished_array_tm); ?></td>
      </tr>
      <tr>
          <td style="vertical-align: top;"><?php echo $tb_result; ?></td>
          <td style="vertical-align: top;"><?php echo $tm_result; ?></td>
      </tr>
    </table>
    <?php
    return ob_get_clean();
  }


}

Livegame_Table_Result::init();

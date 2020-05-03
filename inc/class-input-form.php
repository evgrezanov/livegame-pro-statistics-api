<?php


class Livegame_Input_Form {

  public static function init(){
    add_shortcode('lgp_input_form', [__CLASS__, 'lgp_input_form']);
  }

  public static function lgp_generate_league_selecbox(){
    $soccer_leagues = get_terms('sports', 'child_of=2&hide_empty=0');
    $hokkey_leagues = get_terms('sports', 'child_of=4&hide_empty=0');
    ?>
<!-- league -->
<h4 class="mb-3">Лига или чемпионат</h4>
<div class="form-check d-block my-3">
    <div class="col-md-12 my-3">
        <select class="custom-select" id="sport_league" name="sport_league"
            aria-label="Example select with button addon">
            <optgroup label="Хоккей">
                <?php
                  if (!empty($hokkey_leagues)):
                    foreach ($hokkey_leagues as $league): ?>
                <option value="<?php echo $league->slug; ?>"><?php echo $league->name; ?></option>
                <?php
                    endforeach;
                  endif;
                ?>
            </optgroup>
            <optgroup label="Футбол">
                <?php
                  if (!empty($soccer_leagues)):
                    foreach ($soccer_leagues as $league): ?>
                <option value="<?php echo $league->slug; ?>"><?php echo $league->name; ?></option>
                <?php
                    endforeach;
                  endif;
                ?>
            </optgroup>
        </select>
    </div>
</div>
<hr class="mb-4">
<?php
  }

  public static function lgp_generate_teams_inputs(){
    ?>
<!-- teams -->
<h4 class="mb-3">Команды</h4>
<div class="form-check d-block my-3">
    <input checked disabled class="form-check-input" type="checkbox" value="1" id="allTeams" name="allTeams">
    <label class="form-check-label" for="allTeams">Выбрать команды</label>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="host_team">Хозяева</label>
        <select disabled class="custom-select d-block w-100" required="required" id="host_team" name="host_team">
            <option value="1">Команда 1</option>
            <option value="2">Команда 2</option>
            <option value="3">Команда 3</option>
            <option value="4">Команда 4</option>
            <option value="5">Команда 5</option>
            <option value="6">Команда 6</option>
            <option value="7">Команда 7</option>
            <option value="8">Команда 8</option>
        </select>
        <small class="text-muted">Команда принимающая матч</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="guest_team">Гости</label>
        <select class="custom-select d-block w-100" required="required" id="guest_team" name="guest_team" disabled>
            <option value="1">Команда 1</option>
            <option value="2">Команда 2</option>
            <option value="3">Команда 3</option>
            <option value="4">Команда 4</option>
            <option value="5">Команда 5</option>
            <option value="6">Команда 6</option>
            <option value="7">Команда 7</option>
        </select>
        <small class="text-muted">Команда гостей</small>
    </div>
</div>
<hr class="mb-4">
<?php
  }

  public static function lgp_generate_times_inputs(){
  ?>
<!-- times -->
<h4 class="mb-3">Время</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="time1">Минута матча 1</label>
        <input class="form-control" type="number" min="0" max="89" name="time1" id="time1" value="0" equired="required">
        <small class="text-muted">С какой минуты учитывать</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="time2">Минута матча 2</label>
        <input class="form-control" type="number" min="1" max="90" name="time2" id="time2" value="90"
            required="required">
        <small class="text-muted">До какой минуты</small>
    </div>
</div>
<hr class="mb-4">
<?php
  }

  public static function lgp_generate_tally_inputs(){
  ?>
<!-- tally begin-->
<h4 class="mb-3">Счет</h4>
<div class="form-check d-block my-3">
    <input class="form-check-input" type="checkbox" value="1" id="all_tally" name="all_tally">
    <label class="form-check-label" for="all_tally">Указать счет</label>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="host_tally">Голов у хозяев</label>
        <input type="number" class="form-control" min="0" name="host_tally" id="host_tally" value="0" placeholder=""
            required disabled>
        <small class="text-muted">количество мячей забитое командой хозяев на указанную минуту</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="guest_tally">Голов у гостей</label>
        <input type="number" class="form-control" min="0" name="guest_tally" id="guest_tally" value="0" placeholder=""
            required disabled>
        <small class="text-muted">количество мячей забитое командой гостей на указанную минуту</small>
    </div>
</div>
<hr class="mb-4">
<!-- tally end-->
<?php
  }

  public static function lgp_generate_markets(){
  ?>
<!-- markets begin-->
<h4 class="mb-3">Выберите рынок</h4>
<div class="alert alert-warning" role="alert">
    Можно указать только один рынок.
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <select class="custom-select" id="marketType" name="market_type">
            <option value="total">Тотал</option>
            <option value="handicap">Фора1</option>
            <option value="handicap2">Фора2</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <div class="col-md-6" id="marketValue">
            <input class="form-control" type="number" min="0.5" max="19.5" value="0.5" step="1" name="market_value"
                id="market_value">
        </div>
    </div>
</div>
<!-- markets end-->
<?php
  }

  public static function lgp_generate_handycap_inputs(){
    ?>
<!-- handycap begin-->
<h4 class="mb-3">Фора</h4>
<div class="alert alert-warning" role="alert">
    Вы можете указать фору только одной из команд.
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-check d-block col-md-6 my-3">
            <input class="form-check-input" type="checkbox" value="1" id="use_handicap" name="use_handicap">
            <label class="form-check-label" for="use_handicap">Указать фору (гандикап) хозяев</label>
        </div>
        <input type="number" class="form-control" min="-3.5" max="3.5" name="host_handicap" id="host_handicap" value="0"
            placeholder="" required disabled>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-check d-block col-md-6 my-3">
            <input class="form-check-input" type="checkbox" value="1" id="use_handicap2" name="use_handicap2">
            <label class="form-check-label" for="use_handicap2">Указать фору (гандикап) гостей</label>
        </div>
        <input type="number" class="form-control" min="-3.5" max="3.5" name="guest_handicap" id="guest_handicap"
            value="0" placeholder="" required disabled>
    </div>
</div>
<hr class="mb-4">
<!-- handycap end-->
<?php
  }

  public static function lgp_generate_total_inputs(){
    ?>
<!-- total begin -->
<h4 class="mb-3">Рынки</h4>
<div class="alert alert-warning" role="alert">
    При указании тотала, значения форы не учитываются.
</div>
<div class="row">
    <div class="form-check d-block col-md-6 my-3">
        <input class="form-check-input" type="checkbox" value="1" id="use_total" name="use_total">
        <label class="form-check-label" for="use_total">Указать тотал</label>
    </div>
    <div class="col-md-6" id="totalDiv">
        <input class="form-control" type="number" min="0.5" max="19.5" value="0.5" step="1" name="total" id="total"
            value="0.5">
        <small class="text-muted">необходимое значение <strong>тотала</straong></small>
    </div>
</div>
<hr class="mb-4">
<!-- total end-->
<?php
  }

  public static function lgp_generate_sidebar(){
  ?>
<!-- sidebar -->
<div class="col-md-4 order-md-1 mb-4">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
            aria-controls="v-pills-home" aria-selected="true">Параметры</a>
        <a class="nav-link" id="v-pills-result-tab" data-toggle="pill" href="#v-pills-result" role="tab"
            aria-controls="v-pills-result" aria-selected="false">Результат</a>
        <a class="nav-link" id="v-pills-totalb-tab" data-toggle="pill" href="#v-pills-totalb" role="tab"
            aria-controls="v-pills-totalb" aria-selected="false">Тотал Больше</a>
        <a class="nav-link" id="v-pills-totalm-tab" data-toggle="pill" href="#v-pills-totalm" role="tab"
            aria-controls="v-pills-totalm" aria-selected="false">Тотал Меньше</a>
    </div>
</div>
<?php
  }

  //public static function lgp_generate_result_tab_content(){


  //}

  public static function lgp_input_form(){
      ob_start();
      ?>
<div class="row" id="lgpFormInput">

    <div class="col-md-8 order-md-2">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <!-- form -->
                <form method="post" id="lgpFilterForm" class="needs-validation" novalidate>

                    <?php self::lgp_generate_league_selecbox(); ?>

                    <?php self::lgp_generate_teams_inputs(); ?>

                    <?php self::lgp_generate_tally_inputs(); ?>

                    <?php self::lgp_generate_times_inputs(); ?>

                    <?php self::lgp_generate_markets(); ?>

                    <div class="response"></div>
                    <div id="lgp-loader" class="loader" style="display:none;"></div>
                    <input type="submit" class="btn btn-primary btn-lg btn-block" id="lgp-filter-form-btn"
                        value="Рассчитать">
                </form>
                <!-- form END -->
            </div>

            <div class="tab-pane fade" id="v-pills-result" role="tabpanel" aria-labelledby="v-pills-result-tab">
                <div id="header_res"></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Лига: <span id="league_r"></span> </li>
                    <li class="list-group-item">Период: <span id="period_r"></span> </li>
                    <li class="list-group-item">Счет: <span id="tally_r"></span> </li>
                    <li class="list-group-item">Рынок: <span id="market_r"></span> </li>
                    <li class="list-group-item">
                        Вероятность ТБ <span id="tb_r"></span>
                        <div class="progress tb">
                            <div id="progress-tb" class="progress-bar bg-success" role="progressbar" style="width: 0%;"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        Вероятность ТМ <span id="tm_r"></span>
                        <div class="progress tm">
                            <div id="progress-tm" class="progress-bar bg-warning" role="progressbar" style="width: 0%;"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="tab-pane fade" id="v-pills-totalb" role="tabpanel" aria-labelledby="v-pills-totalb-tab">

                <div class="table-responsive">
                    <table class="table" id="lgp_tb">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Матч</th>
                                <th scope="col">Счет</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Гол</th>
                                <th scope="col">Минута</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>

            <div class="tab-pane fade" id="v-pills-totalm" role="tabpanel" aria-labelledby="v-pills-totalm-tab">

                <div class="table-responsive">
                    <table class="table" id="lgp_tm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Матч</th>
                                <th scope="col">Счет</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Гол</th>
                                <th scope="col">Минута</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <?php self::lgp_generate_sidebar(); ?>

</div>

<?php
      return ob_get_clean();
  }

}

Livegame_Input_Form::init();
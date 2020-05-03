/**
 * from https://gist.github.com/uptimizt/34ce8e582e256eb2c3c3b612b23188a0
 */
var FormAjax = function (elementSelector, ep, args = []) {
  if (document.querySelector(elementSelector)) {
    var form = document.querySelector(elementSelector);
  } else {
    return false;
  }

  var restApiEndpoint = wpApiSettings.root + ep;

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    var formData = new FormData(form);
    var submitBtnText = "";
    var message = "";
    var submitBtn = form.querySelector('input[type="submit"]');
    var request = new XMLHttpRequest();

    submitBtn.disabled = true;
    submitBtnText = submitBtn.value;
    submitBtn.value = "Подождите...";

    request.open("POST", restApiEndpoint);
    request.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
    request.send(formData);

    request.onload = function () {
      var message = "";

      if (request.status == 200) {
        // analyze HTTP status of the response

        var response = JSON.parse(request.response);

        if (
          args.successCallback &&
          typeof args.successCallback === "function"
        ) {
          args.successCallback(response);
          return;
        }

        if (response.success) {
          message = '<p class="success">' + response.data.message + "</p>";
        } /*{
              message = '<div class="error_login"><p class="error">' + response.data.message + '</p></div>';
            }*/

        console.log(response);

        form.querySelector(".response").innerHTML = message;

        /*if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }*/

        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;

        LGP_StatisticRequest.processing_success(response);
      } else if (request.status == 401) {
        form.querySelector(".response").innerHTML =
          '<div class="error_login"><p class="error">' +
          "Вы должны быть авторизованы на сайте чтобы выполнять выборку.</p></div>";
        // Снимаем ограничение с кнопки и возвращаем прежнее значение
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
      } else {
        form.querySelector(".response").innerHTML =
          '<div class="error_login"><p class="error">' +
          "Что-то пошло не так. Пожалуйста попробуйте еще раз.</p></div>";
        // Снимаем ограничение с кнопки и возвращаем прежнее значение
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
      }
    };

    request.onerror = function () {
      submitBtn.disabled = false;
      submitBtn.value = submitBtnText;
    };
  });
};

var LGP_StatisticRequest = {
  get_params: function () {
    var arrayResult = {};

    var champ_code_select = document.getElementById("sport_league");
    arrayResult["sport_league"] =
      champ_code_select.options[champ_code_select.selectedIndex].value;

    arrayResult["allTeams"] = document.getElementById("allTeams").value;

    var host_team_select = document.getElementById("host_team");
    arrayResult["host_team"] =
      host_team_select.options[host_team_select.selectedIndex].value;

    var guest_team_select = document.getElementById("guest_team");
    arrayResult["guest_team"] =
      guest_team_select.options[guest_team_select.selectedIndex].value;

    arrayResult["time1"] = document.getElementById("time1").value;
    arrayResult["time2"] = document.getElementById("time2").value;

    arrayResult["all_tally"] = document.getElementById("all_tally").value;
    arrayResult["host_tally"] = document.getElementById("host_tally").value;
    arrayResult["guest_tally"] = document.getElementById("guest_tally").value;

    var market_type_select = document.getElementById("marketType");
    arrayResult["market_type"] =
      market_type_select.options[market_type_select.selectedIndex].value;

    arrayResult["market_value"] = document.getElementById("market_value").value;

    //add none late
    return arrayResult;
  },

  processing_success: function (response) {
    // add rows to table at total > TAB
    let res_total_b = response.total_b;
    $.each(res_total_b, function (key, val) {
      $(
        '<tr><td id="' +
          val.id +
          '">' +
          val.id +
          "</td><td>" +
          val.team1 +
          " - " +
          val.team2 +
          " <small>" +
          val.match_full_date +
          "</small></td><td>" +
          val.tally +
          "</td><td>" +
          val.tally_goals_name +
          "</td><td>" +
          val.tally_goals_tally_by_man +
          "</td><td>" +
          val.tally_goals_time_of_tally +
          "</td><tr>"
      ).appendTo("#lgp_tb");
    });
    document.querySelector("#v-pills-totalb-tab").innerHTML =
      response.params.market_name +
      ' Больше <span class="badge badge-pill badge-primary" data-toggle="tooltip" data-placement="top" title="Количество голов">' +
      response.total_b_count +
      '</span><span class="badge badge-pill badge-success" data-toggle="tooltip" data-placement="top" title="Количество матчей">' +
      response.total_game_b +
      "</span>";

    // add rows to table at total < TAB
    let res_total_m = response.total_m;
    $.each(res_total_m, function (key, val) {
      $(
        '<tr><td id="' +
          val.id +
          '">' +
          val.id +
          "</td><td>" +
          val.team1 +
          " - " +
          val.team2 +
          " <small>" +
          val.match_full_date +
          "</small></td><td>" +
          val.tally +
          "</td><td>" +
          val.tally_goals_name +
          "</td><td>" +
          val.tally_goals_tally_by_man +
          "</td><td>" +
          val.tally_goals_time_of_tally +
          "</td><tr>"
      ).appendTo("#lgp_tm");
    });
    document.querySelector("#v-pills-totalm-tab").innerHTML =
      response.params.market_name +
      ' Меньше <span class="badge badge-pill badge-primary" data-toggle="tooltip" data-placement="top" title="Количество голов">' +
      response.total_m_count +
      '</span><span class="badge badge-pill badge-success" data-toggle="tooltip" data-placement="top" title="Количество матчей">' +
      response.total_game_m +
      "</span>";

    // display result tab
    $('#v-pills-tab a[href="#v-pills-result"]').tab("show");

    // add text
    document.querySelector("#header_res").innerHTML =
      '<h1>Минимальный необходимый коэффициент: <span class="badge badge-success">' +
      response.min_kf +
      "</span></h1>";
    document.querySelector("#league_r").innerHTML =
      '<a href="' +
      response.params.sports_url +
      '">' +
      response.params.sports_name +
      "</a>";
    document.querySelector("#period_r").innerHTML = response.params.period;
    document.querySelector("#tally_r").innerHTML = response.params.tally;
    document.querySelector("#market_r").innerHTML = response.params.market;
    document.querySelector("#tb_r").innerHTML = response.total_b_prcnt;
    document.querySelector("#tm_r").innerHTML = response.total_m_prcnt;

    // run progress bars tb & tm
    $("#progress-tb").animate(
      {
        width: response.total_b_prcnt,
      },
      3500
    );
    $("#progress-tm").animate(
      {
        width: response.total_m_prcnt,
      },
      3500
    );
  },
};

document.addEventListener("DOMContentLoaded", function () {
  FormAjax(
    "#lgpFilterForm",
    "lgp_api/v1/get_statistic",
    LGP_StatisticRequest.get_params()
  );
});

// choose checkbox all team
document.getElementById("allTeams").onchange = function () {
  document.getElementById("host_team").disabled = !this.checked;
  document.getElementById("guest_team").disabled = !this.checked;
};

// choose checkbox all tally
document.getElementById("all_tally").onchange = function () {
  document.getElementById("host_tally").disabled = !this.checked;
  document.getElementById("guest_tally").disabled = !this.checked;
};

// change market type
$("#marketType").on("change", function () {
  if ($(this).val() == "total") {
    $("#market_value").attr({
      min: "0.5",
      max: "19.5",
    });
    console.log("total");
  } else {
    $("#market_value").attr({
      min: "-3.5",
      max: "3.5",
    });
    console.log("handi");
  }
});
$("#marketType").trigger("change");

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

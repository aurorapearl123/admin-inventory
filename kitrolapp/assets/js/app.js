// Dom7
var $$ = Dom7;
var base_url = "https://cloudmd.net/kitrol/";
//var base_url = "http://localhost/project/special/kitrol/";
// Framework7 App main instance
var app = new Framework7({
  root: "#app", // App root element
  id: "m.xynpax.kitrol", // App bundle ID
  name: "Kitrol", // App name
  theme: "ios",

  // App root data
  data: function() {
    return {
      user: {
        firstName: "John",
        lastName: "Doe"
      }
    };
  },

  // App routes
  routes: routes,
  touch: {
    // Disable fast clicks
    fastClicks: true
  }
});

// Init/Create main view
var mainView = app.views.create(".view-main", {
  url: "/",
  on: {
    pageInit: function() {
      //console.log("testing");
      // mainView.openModal(".login-screen", true);
      $$(document).on("click", "#login-button", function() {
        //console.log("The pin");
        var pin = $$("#pin").val();
        //console.log(pin);

        app.preloader.show();
        setTimeout(function() {
          //request to server
          login(app, pin);
          // app.preloader.hide();
          // mainView.router.navigate("/dashboard/");
        }, 300);

        //app.preloader;
      });
    }
  }
});

if ((app.views.main.router.currentRoute.url = "/")) {
  //do some thing
}

function login(app, pin) {
  app.request.post(
    base_url + "api/login",
    { pin: pin },
    function(xhr, status) {
      // console.log(xhr);
      var server_data = JSON.parse(xhr);
      var token = server_data.data.token;
      localStorage.setItem("token", token);
      app.preloader.hide();
      mainView.router.navigate("/dashboard/");
      console.log("Load was performed");
    },
    function(data) {
      if (data.status == 400 || data.status == 404) {
        app.preloader.hide();
        alert("Pin Not Found");
        return false;
      } else {
        console.log("the token");
        console.log(data);
        app.preloader.hide();
        mainView.router.navigate("/dashboard/");
        console.log("Load was performed");
      }
    }
  );
}

// Option 1. Using one 'page:init' handler for all pages
$$(document).on("page:init", function(e) {
  // Do something here when page loaded and initialized
  //set up authorization
  var token = localStorage.getItem("token");
  //console.log("the token");
  //console.log(token);
  app.request.setup({
    headers: {
      Authorization: token
    }
  });

  var page = e.detail.name;
  if (page == "deposit") {
    console.log("this is the page", page);

    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }

    current_date = yyyy + "-" + mm + "-" + dd;
    console.log("current date");
    console.log(current_date);

    app.preloader.show();
    setTimeout(function() {
      //request to server
      app.request.get(base_url + "api/deposits/" + current_date, function(
        data
      ) {
        $$("#testing-list").html("");
        //console.log(data);
        var deposits = JSON.parse(data);
        //console.log(deposits.data);
        var deposits_data = deposits.data;
        for (var i = 0; i < deposits_data.length; i++) {
          console.log(deposits_data[i]["accountName"]);
          today(deposits_data[i]["accountName"], deposits_data[i]["email"]);
        }

        //get month

        //app.preloader.hide();
      });

      //get monthly deposits
      monthlyDeposits(app, base_url);

      februaryDeposits(app, base_url);

      // app.preloader.hide();
      // mainView.router.navigate("/dashboard/");
    }, 300);
  }

  if (page == "sales-report") {
    console.log("sales-report page");
    getSalesReport();
    getSalesToday();
    getTotalSalesPerMonth();
  }

  if (page == "inventory") {
    console.log("inventory this");
    //get deafault
    getDefaultInventory();
  }

  if (page == "dashboard") {
    getDashboard();
  }
});

function getDashboard() {
  app.preloader.show();
  setTimeout(function() {
    //request to server
    app.request.get(base_url + "api/inventory", function(data) {
      //console.log(data);
      var deposits = JSON.parse(data);
      //console.log(deposits.data);
      var deposits_data = deposits.data;
      //console.log(deposits_data);
      //console.log(deposits_data.DIESEL);
      var diesel = parseFloat(deposits_data.DIESEL.total).toFixed(2);
      var premium = parseFloat(deposits_data.PREMIUM.total).toFixed(2);
      var unleaded = parseFloat(deposits_data.UNLEADED.total).toFixed(2);
      var premium_price =
        deposits_data.PREMIUM.price == null
          ? 00
          : parseFloat(deposits_data.PREMIUM.price).toFixed(2);

      var unleaded_price =
        deposits_data.UNLEADED.price == null
          ? 00
          : parseFloat(deposits_data.UNLEADED.price).toFixed(2);

      var diesel_price =
        deposits_data.DIESEL.price == null
          ? 00
          : parseFloat(deposits_data.DIESEL.price).toFixed(2);

      //Price: ₱45 L
      $$("#id-dashboard-diesel").text(diesel);
      $$("#id-diesel-price").text(
        "Price: ₱" + diesel_price + " " + deposits_data.DIESEL.umsr
      );
      $$("#id-dashboard-premium").text(premium);
      $$("#id-premium-price").text(
        "Price: ₱" + premium_price + " " + deposits_data.PREMIUM.umsr
      );
      $$("#id-dashboard-unleaded").text(unleaded);
      $$("#id-unleaded-price").text(
        "Price: ₱" + unleaded_price + " " + deposits_data.UNLEADED.umsr
      );

      //get today
      var current_date = new Date();
      var dd = current_date.getDate();
      var mm = current_date.getMonth() + 1; //January is 0!
      var yyyy = current_date.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      //january
      january_date = yyyy + "-" + mm + "-" + dd;

      app.request.get(base_url + "api/inventorydate/" + january_date, function(
        data
      ) {
        var inventory = JSON.parse(data);
        var total =
          inventory.total == null ? 0 : parseFloat(inventory.total).toFixed(2);

        $$("#dashboard-today").text(total + " " + inventory.umsr);

        // app.preloader.hide();
      });

      //app.preloader.hide();
      //getJanInventory();
      app.preloader.hide();
    });
  }, 300);
}

function getDefaultInventory() {
  console.log("get default invetory");
  app.preloader.show();
  setTimeout(function() {
    //request to server
    app.request.get(base_url + "api/inventory", function(data) {
      //console.log(data);
      var deposits = JSON.parse(data);
      //console.log(deposits.data);
      var deposits_data = deposits.data;
      console.log(deposits_data);
      console.log(deposits_data.DIESEL);
      var diesel = parseFloat(deposits_data.DIESEL.total).toFixed(2);
      var premium = parseFloat(deposits_data.PREMIUM.total).toFixed(2);
      var unleaded = parseFloat(deposits_data.UNLEADED.total).toFixed(2);
      $$("#id-diesel").text(diesel + " " + deposits_data.DIESEL.umsr);
      $$("#id-premium").text(premium + " " + deposits_data.PREMIUM.umsr);
      $$("#id-unleaded").text(unleaded + " " + deposits_data.UNLEADED.umsr);

      //app.preloader.hide();
      getJanInventory();
      //set default tab1
      var current_date = new Date();
      var dd = current_date.getDate();
      var mm = current_date.getMonth() + 1; //January is 0!
      var yyyy = current_date.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      app.preloader.show();
      january_date = yyyy + "-" + "01";
      var _the_date = moment(january_date).format("MMMM");
      $$("#id-date-name").text(_the_date);
      inventoryByDate(january_date);

      app.preloader.hide();
    });
  }, 300);

  // $$("#id-tab-jan").on("click", function() {
  //   console.log("hello click");
  // });

  $$(document).on("click", "#id-tab-jan", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "01";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-march", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "03";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-april", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "04";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-may", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "05";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-jun", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "06";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-july", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "07";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-august", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "08";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-september", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "09";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-october", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "10";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-november", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "11";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-december", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "12";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    inventoryByDate(january_date);
  });

  $$(document).on("click", "#id-tab-feb", function() {
    app.preloader.show();
    console.log("get data for feb testing me this");
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }

    //january
    january_date = yyyy + "-" + "02";

    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    //display inventory by date
    inventoryByDate(january_date);
  });
}

function inventoryByDate(january_date) {
  console.log("DATE");
  console.log(january_date);
  $$("#ul-data-inventory").html("");
  app.request.get(base_url + "api/inventorydetails/" + january_date, function(
    data
  ) {
    var inventory = JSON.parse(data);
    console.log("hello there");
    console.log(inventory.data);
    var _data = inventory.data;
    var total_diesel = 0;
    var total_premium = 0;
    var total_unleaded = 0;
    var diesel_umsr = "";
    var premium_umsr = "";
    var unleaded_umsr = "";
    for (var i = 0; i < _data.length; i++) {
      // if (_data[i].name == "DIESEL") {
      //console.log(_data[i].name);

      var _the_date = moment(_data[i].dateInserted).format("MM DD YYYY h A");
      console.log("the date");
      console.log(_the_date);
      $$("#ul-data-inventory").append(
        $$("<li>").append(
          $$("<div>")
            .attr("class", "item-content")
            .append(
              $$("<div>")
                .attr("class", "item-inner item-cell")
                .append(
                  $$("<div>")
                    .attr("class", "item-row margin-top-5")
                    .append(
                      $$("<div>")
                        .attr("class", "item-cell")
                        .append(
                          $$("<div>")
                            .attr("class", "item-after no-padding-left")
                            .text(_data[i].qty + " L")
                        )
                        .append(
                          $$("<span>")
                            .attr("class", "progressbar color-red margin-top-5")
                            .attr("data-progress", "70")
                        )
                    )
                    .append(
                      $$("<div>")
                        .attr("class", "item-cell")
                        .append(
                          $$("<div>")
                            .attr("class", "item-after no-padding-left")
                            .text("")
                        )
                        .append(
                          $$("<span>")
                            .attr("class", "")
                            .attr("data-progress", "40")
                        )
                    )
                    .append(
                      $$("<div>")
                        .attr("class", "item-cell")
                        .append(
                          $$("<div>")
                            .attr("class", "item-after no-padding-left")
                            .text("")
                        )
                        .append(
                          $$("<span>")
                            .attr("class", "")
                            .attr("data-progress", "30")
                        )
                    )
                )
                .append(
                  $$("<div>")
                    .attr("class", "item-footer margin-top-10")
                    .text(_data[i].name)
                    .append(
                      $$("<span>")
                        .attr("class", "float-right")
                        .text(_the_date)
                    )
                )
            )
        )
      );
      //}

      if (_data[i].name == "DIESEL") {
        total_diesel += +_data[i].qty;
        diesel_umsr = _data[i].umsr;
      }
      if (_data[i].name == "PREMIUM") {
        total_premium += +_data[i].qty;
        premium_umsr = _data[i].umsr;
      }
      if (_data[i].name == "UNLEADED") {
        total_unleaded += +_data[i].qty;
        unleaded_umsr = _data[i].umsr;
      }
    }
    console.log("the total");
    console.log(total_diesel);
    $$("#inventory-total-diesel").text(
      parseFloat(total_diesel).toFixed(2) + " " + diesel_umsr
    );
    $$("#inventory-total-premium").text(
      parseFloat(total_premium).toFixed(2) + " " + premium_umsr
    );
    $$("#inventory-total-unleaded").text(
      parseFloat(total_unleaded).toFixed(2) + " " + unleaded_umsr
    );
    app.preloader.hide();

    // app.preloader.hide();
  });
}

function getJanInventory() {
  var current_date = new Date();
  var dd = current_date.getDate();
  var mm = current_date.getMonth() + 1; //January is 0!
  var yyyy = current_date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }
  //january
  january_date = yyyy + "-" + "01";
  $$(".year").text(yyyy);

  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    var inventory = JSON.parse(data);

    $$("#id-jan-total").html("");
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-jan-total").text(total + " " + umsr);

    // app.preloader.hide();
  });

  january_date = yyyy + "-" + "02";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    var inventory = JSON.parse(data);

    $$("#id-feb-total").html(" ");
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-feb-total").text(total + " " + umsr);

    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "03";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);

    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-march-total").text(total + " " + umsr);

    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "04";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);

    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-april-total").text(total + " " + umsr);

    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "05";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-may-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "06";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-june-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "07";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-july-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "08";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-august-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "09";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-september-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "10";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-october-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "11";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-november-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  january_date = yyyy + "-" + "12";
  //request to server
  app.request.get(base_url + "api/inventorydate/" + january_date, function(
    data
  ) {
    //console.log("feb");
    var inventory = JSON.parse(data);
    var total =
      inventory.total == null ? "N/A" : parseFloat(inventory.total).toFixed(2);
    var umsr = inventory.umsr == null ? "" : inventory.umsr;
    $$("#id-december-total").text(total + " " + umsr);
    //app.preloader.hide();
  });

  //console.log("get whole month", january_date);
}

function getDeposits(deposits) {
  for (deposit in deposits) {
    console.log(deposit);
  }
}

function today(accountName, email) {
  $$("#testing-list").html("");
  $$("#testing-list").append(
    $$("<div>")
      .attr("class", "item-content")
      .append(
        $$("<div>")
          .attr("class", "item-media")
          .append($$("<i>").attr("class", "icon la la-bank"))
      )
      .append(
        $$("<div>")
          .attr("class", "item-inner item-cell")
          .append(
            $$("<div>")
              .attr("class", "item-row")
              .append(
                $$("<div>")
                  .attr("class", "item-cell")
                  .append(
                    $$("<div>")
                      .attr("class", "item-title")
                      .text(accountName)
                  )
              )
          )
          .append(
            $$("<div>")
              .attr("class", "item-row")
              .append(
                $$("<div>")
                  .attr("class", "item-cell no-margin-left")
                  .append(
                    $$("<div>")
                      .attr("class", "item-after no-padding-left")
                      .text(email)
                  )
              )
          )
          .append(
            $$("<div>")
              .attr("class", "item-footer")
              .text("5:16 PM")
          )
      )
  );
}

function monthlyDeposits(app, base_url) {
  var current_date = new Date();
  var dd = current_date.getDate();
  var mm = current_date.getMonth() + 1; //January is 0!
  var yyyy = current_date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }

  //january
  january_date = yyyy + "-" + "01";
  console.log("get whole month", january_date);

  var _the_date = moment(january_date).format("MMMM  YYYY");

  app.request.get(base_url + "api/deposits/" + january_date, function(data) {
    //$$("#testing-list").html("");
    //console.log(data);
    var deposits = JSON.parse(data);
    console.log("the count");
    //console.log(deposits.data);
    var deposits_data = deposits.data;
    console.log(deposits_data.length);
    if (deposits_data.length != 0) {
      $$("#id-month")
        .append("<span>")
        .text(_the_date);
      getJanuary(deposits_data);
    }

    //get month
  });
}

function februaryDeposits(app, base_url) {
  var current_date = new Date();
  var dd = current_date.getDate();
  var mm = current_date.getMonth() + 1; //January is 0!
  var yyyy = current_date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }

  //january
  january_date = yyyy + "-" + "02";
  console.log("get whole month", january_date);

  var _the_date = moment(january_date).format("MMMM  YYYY");

  app.request.get(base_url + "api/deposits/" + january_date, function(data) {
    //$$("#testing-list").html("");
    //console.log(data);
    var deposits = JSON.parse(data);
    console.log("the count");
    //console.log(deposits.data);
    var deposits_data = deposits.data;
    console.log(deposits_data.length);
    if (deposits_data.length != 0) {
      $$("#id-february")
        .append("<span>")
        .text(_the_date);
      getFebruary(deposits_data);
    }

    //get month

    app.preloader.hide();
  });
}

function getJanuary(deposits_data) {
  $$("#ul-jan").html("");

  for (var i = 0; i < deposits_data.length; i++) {
    //console.log(deposits_data[i]["accountName"]);
    // $$("#testing-jan").html("");
    $$("#testing-jan")
      .append("<li>")
      .append(
        $$("<div>")
          .attr("class", "item-content")
          .append(
            $$("<div>")
              .attr("class", "item-media")
              .append($$("<i>").attr("class", "icon la la-bank"))
          )
          .append(
            $$("<div>")
              .attr("class", "item-inner item-cell")
              .append(
                $$("<div>")
                  .attr("class", "item-row")
                  .append(
                    $$("<div>")
                      .attr("class", "item-cell")
                      .append(
                        $$("<div>")
                          .attr("class", "item-title")
                          .text(deposits_data[i]["accountName"])
                      )
                  )
              )
              .append(
                $$("<div>")
                  .attr("class", "item-row")
                  .append(
                    $$("<div>")
                      .attr("class", "item-cell no-margin-left")
                      .append(
                        $$("<div>")
                          .attr("class", "item-after no-padding-left")
                          .text(deposits_data[i]["email"])
                      )
                  )
              )
              .append(
                $$("<div>")
                  .attr("class", "item-footer")
                  .text("5:16 PM")
              )
          )
      );
    //today(deposits_data[i]["accountName"], deposits_data[i]["email"]);
  }
}

function getFebruary(deposits_data) {
  $$("#ul-february").html("");

  for (var i = 0; i < deposits_data.length; i++) {
    //console.log(deposits_data[i]["accountName"]);
    // $$("#testing-jan").html("");
    $$("#ul-february")
      .append("<li>")
      .append(
        $$("<div>")
          .attr("class", "item-content")
          .append(
            $$("<div>")
              .attr("class", "item-media")
              .append($$("<i>").attr("class", "icon la la-bank"))
          )
          .append(
            $$("<div>")
              .attr("class", "item-inner item-cell")
              .append(
                $$("<div>")
                  .attr("class", "item-row")
                  .append(
                    $$("<div>")
                      .attr("class", "item-cell")
                      .append(
                        $$("<div>")
                          .attr("class", "item-title")
                          .text(deposits_data[i]["accountName"])
                      )
                  )
              )
              .append(
                $$("<div>")
                  .attr("class", "item-row")
                  .append(
                    $$("<div>")
                      .attr("class", "item-cell no-margin-left")
                      .append(
                        $$("<div>")
                          .attr("class", "item-after no-padding-left")
                          .text(deposits_data[i]["email"])
                      )
                  )
              )
              .append(
                $$("<div>")
                  .attr("class", "item-footer")
                  .text("5:16 PM")
              )
          )
      );
    //today(deposits_data[i]["accountName"], deposits_data[i]["email"]);
  }
}




































//Add START =========================================================================

  $$(document).on("click", "#id-tab-jan", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "01";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-march", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "03";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-april", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "04";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-may", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "05";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-jun", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "06";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-july", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "07";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-august", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "08";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-september", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "09";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-october", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "10";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-november", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "11";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-december", function() {
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }
    app.preloader.show();
    january_date = yyyy + "-" + "12";
    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    getSalesReport(january_date);
  });

  $$(document).on("click", "#id-tab-feb", function() {
    app.preloader.show();
    console.log("get data for feb testing me this");
    var current_date = new Date();
    var dd = current_date.getDate();
    var mm = current_date.getMonth() + 1; //January is 0!
    var yyyy = current_date.getFullYear();

    if (dd < 10) {
      dd = "0" + dd;
    }

    if (mm < 10) {
      mm = "0" + mm;
    }

    //january
    january_date = yyyy + "-" + "02";

    var _the_date = moment(january_date).format("MMMM");
    $$("#id-date-name").text(_the_date);
    //display inventory by date
    getSalesReport(january_date);
  });

























function getSalesReport(date) {
  var ul_items = $$("#ul-data-inventory");
  var total_span = $$("#total_span");
  app.preloader.show();
  setTimeout(function() {
    //request to server
    app.request.get(base_url + "api/sales/"+date, function(data) {
      // $$("#testing-list").html("");
      //console.log(data);
      var sales = JSON.parse(data);
      //console.log(deposits.data);
      var sales_data = sales.data;
      // alert(sales_data);
      var list = "";
      var total = 0;
      for (var i = 0; i < sales_data.length; i++) {
        console.log(sales_data[i]["collectionNo"]);
        list += "<li>";
        list += '<div class="item-content">';
        list += '<div class="item-inner">';
        list += '<div class="item-title">';
        list += sales_data[i]["collectionDate"];
        list += '<div class="item-footer">Collection No: ';
        list += sales_data[i]["collectionNo"];
        list += "</div>";
        list += "</div>";
        list += '<div class="item-after">';
        list += sales_data[i]["totalAmount"];
        list += "</div>";
        list += "</div>";
        list += "</div>";
        list += "</li>";

        total += parseFloat(sales_data[i]["totalAmount"]);
      }

      app.preloader.hide();

      ul_items.html(list);
      total_span.html(parseFloat(total).toFixed(2));
      // "collectionNo": "19000000",
      // "collectionID": "1",
      // "collectionDate": "2019-02-27",
      // "orDate": "2019-02-27",
      // "orNo": "19000000",
      // "arID": "2",
      // "totalAmount": "0.000",
      // "remarks": ""
    });
    // app.preloader.hide();
    // mainView.router.navigate("/dashboard/");
  }, 300);
}

function getSalesToday() {

  var current_date = new Date();
  var dd = current_date.getDate();
  var mm = current_date.getMonth() + 1; //January is 0!
  var yyyy = current_date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }
  app.preloader.show();
  date_today = yyyy + "-" + mm + "-" + dd;

  //=============================================
  console.log(date_today);

  var total_sales_today = $$("#total_sales_today");
  app.preloader.show();
  setTimeout(function() {
    //request to server
    app.request.get(base_url + "api/sales/"+date_today, function(data) {
      
      var sales = JSON.parse(data);
      var sales_data = sales.data;

      var total = 0;
      for (var i = 0; i < sales_data.length; i++) {
        total += parseFloat(sales_data[i]["totalAmount"]);
      }

      app.preloader.hide();

      total_sales_today.html(parseFloat(total).toFixed(2));
      
    });
    
  }, 300);
}



function getTotalSalesPerMonth() {

  var current_date = new Date();
  var dd = current_date.getDate();
  var mm = current_date.getMonth() + 1; //January is 0!
  var yyyy = current_date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }
  app.preloader.show();
  jan = yyyy + "-" + "01";
  feb = yyyy + "-" + "02";
  mar = yyyy + "-" + "03";
  apr = yyyy + "-" + "04";
  may = yyyy + "-" + "05";
  jun = yyyy + "-" + "06";
  jul = yyyy + "-" + "07";
  aug = yyyy + "-" + "08";
  sept = yyyy + "-" + "09";
  oct = yyyy + "-" + "10";
  nov = yyyy + "-" + "11";
  dec = yyyy + "-" + "12";


  var total_sales_jan = $('#id-jan-total');
  var total_sales_feb = $('#id-feb-total');
  var total_sales_mar = $('#id-march-total');
  var total_sales_apr = $('#id-april-total');
  var total_sales_may = $('#id-may-total');
  var total_sales_jun = $('#id-june-total');
  var total_sales_jul = $('#id-july-total');
  var total_sales_aug = $('#id-august-total');
  var total_sales_sept = $('#id-september-total');
  var total_sales_oct = $('#id-october-total');
  var total_sales_nov = $('#id-november-total');
  var total_sales_dec = $('#id-december-total');
  

  //=============================================
  console.log(date_today);

  app.preloader.show();
  setTimeout(function() {
    //request to server
    //january
    app.request.get(base_url + "api/monthly-sales/"+jan, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;
      
      app.preloader.hide();

      total_sales_jan.html(parseFloat(total).toFixed(2));
      
    });

    

    //febuary
    app.request.get(base_url + "api/monthly-sales/"+feb, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;
      
      app.preloader.hide();

      total_sales_feb.html(parseFloat(total).toFixed(2));
      
    });


    //march
    app.request.get(base_url + "api/monthly-sales/"+mar, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_mar.html(parseFloat(total).toFixed(2));
      
    });



    //april
    app.request.get(base_url + "api/monthly-sales/"+apr, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_apr.html(parseFloat(total).toFixed(2));
      
    });




    //may
    app.request.get(base_url + "api/monthly-sales/"+may, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_may.html(parseFloat(total).toFixed(2));
      
    });




    //june
    app.request.get(base_url + "api/monthly-sales/"+jun, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_jun.html(parseFloat(total).toFixed(2));
      
    });




    //july
    app.request.get(base_url + "api/monthly-sales/"+jul, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_jul.html(parseFloat(total).toFixed(2));
      
    });


    //aug
    app.request.get(base_url + "api/monthly-sales/"+aug, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_aug.html(parseFloat(total).toFixed(2));
      
    });

    //sept
    app.request.get(base_url + "api/monthly-sales/"+sept, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_sept.html(parseFloat(total).toFixed(2));
      
    });



    //oct
    app.request.get(base_url + "api/monthly-sales/"+oct, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_oct.html(parseFloat(total).toFixed(2));
      
    });


    //nov
    app.request.get(base_url + "api/monthly-sales/"+nov, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_nov.html(parseFloat(total).toFixed(2));
      
    });


    //dec
    app.request.get(base_url + "api/monthly-sales/"+dec, function(data) {
      
      var sales = JSON.parse(data);
      var total = (sales.total != null)? sales.total:0.00;

      app.preloader.hide();

      total_sales_dec.html(parseFloat(total).toFixed(2));
      
    });




    
  }, 300);
}

//Add END =========================================================================





































































// Circle Progressbar

// // Today diesel
// var tdbar = new ProgressBar.Circle("#td-inv", {
//   color: "#2196f3",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 4,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#2196f3", width: 5 },
//   to: { color: "#2196f3", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "%");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#b3e5fc"
// });

// tdbar.text.style.fontSize = "18px";
// tdbar.animate(0.75);

// // Today premium
// var tpbar = new ProgressBar.Circle("#tp-inv", {
//   color: "#f44336",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 4,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#f44336", width: 5 },
//   to: { color: "#f44336", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "%");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#ffcdd2"
// });
// tpbar.text.style.fontSize = "18px";
// tpbar.animate(0.5);

// // Today unleaded
// var tubar = new ProgressBar.Circle("#tu-inv", {
//   color: "#4caf50",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 8,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#4caf50", width: 5 },
//   to: { color: "#4caf50", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "<i>%</i>");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#c8e6c9"
// });
// tubar.text.style.fontSize = "18px";
// tubar.animate(0.25);

// // January total diesel
// var j1tdbar = new ProgressBar.Circle("#j1td-inv", {
//   color: "#2196f3",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 10,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#2196f3", width: 5 },
//   to: { color: "#2196f3", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "%");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#b3e5fc"
// });

// j1tdbar.text.style.fontSize = "18px";
// j1tdbar.animate(0.75);

// // January Total premium
// var j1tpbar = new ProgressBar.Circle("#j1tp-inv", {
//   color: "#f44336",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 10,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#f44336", width: 5 },
//   to: { color: "#f44336", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "%");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#ffcdd2"
// });
// j1tpbar.text.style.fontSize = "18px";
// j1tpbar.animate(0.5);

// // January Total unleaded
// var j1tubar = new ProgressBar.Circle("#j1tu-inv", {
//   color: "#4caf50",
//   // This has to be the same size as the maximum width to
//   // prevent clipping
//   strokeWidth: 10,
//   trailWidth: 1,
//   easing: "easeInOut",
//   duration: 1400,
//   text: {
//     autoStyleContainer: false
//   },
//   from: { color: "#4caf50", width: 5 },
//   to: { color: "#4caf50", width: 5 },
//   // Set default step function for all animate calls
//   step: function(state, circle) {
//     circle.path.setAttribute("stroke", state.color);
//     circle.path.setAttribute("stroke-width", state.width);

//     var value = Math.round(circle.value() * 100);
//     if (value === 0) {
//       circle.setText("" + "<i>%</i>");
//     } else {
//       circle.setText("<strong>" + value + "</strong><i>%</i>");
//     }
//   },
//   trailColor: "#c8e6c9"
// });
// j1tubar.text.style.fontSize = "18px";
// j1tubar.animate(1);

// // Today chart - Chart.js
// var mode = "index"; // for all chart.js

// var chart2 = document.getElementById("todaySales").getContext("2d");
// var data2 = {
//   labels: ["", "", "", "", "", "", "", "", "", "", "", ""],
//   datasets: [
//     {
//       label: "",
//       backgroundColor: "transparent",
//       pointBackgroundColor: "#f44336",
//       pointHitRadius: 0,
//       pointHoverRadius: 0,
//       pointRadius: 0,
//       borderWidth: 2,
//       borderColor: "#f44336",
//       data: [30, 350, 80, 91, 340, 300, 60, 100, 50, 600, 300, 100],
//       fill: false
//     }
//   ]
// };

// var options2 = {
//   responsive: true,
//   maintainAspectRatio: true,
//   animation: {
//     easing: "easeInOutQuad",
//     duration: 520
//   },
//   hover: {
//     intersect: false
//   },
//   scales: {
//     xAxes: [
//       {
//         gridLines: {
//           color: "transparent",
//           lineWidth: 0
//         }
//       }
//     ],
//     yAxes: [
//       {
//         display: false,
//         beginAtZero: 0,
//         gridLines: {
//           color: "transparent",
//           lineWidth: 0
//         }
//       }
//     ]
//   },
//   elements: {
//     line: {
//       tension: 0.4
//     }
//   },
//   legend: {
//     display: false
//   },
//   tooltips: {
//     enabled: false,
//     backgroundColor: "rgba(0,0,0,0.3)",
//     titleFontColor: "red",
//     caretSize: 5,
//     cornerRadius: 2,
//     xPadding: 10,
//     yPadding: 10,
//     intersect: false
//   },
//   layout: {
//     padding: {
//       left: 0,
//       right: 0,
//       top: 0,
//       bottom: 0
//     }
//   }
// };

// var chartInstance2 = new Chart(chart2, {
//   type: "line",
//   data: data2,
//   options: options2
// });

// // Total this year chart - Chart.js
// var chart = document.getElementById("yearSales").getContext("2d"),
//   gradient = chart.createLinearGradient(0, 0, 0, 600);

// gradient.addColorStop(0, "rgba(244, 67, 54, .1)");
// gradient.addColorStop(0.3, "white");
// gradient.addColorStop(1, "white");

// var data = {
//   labels: [
//     "Jan",
//     "Feb",
//     "Mar",
//     "Apr",
//     "May",
//     "Jun",
//     "Jul",
//     "Aug",
//     "Sep",
//     "Oct",
//     "Nov",
//     "Dec"
//   ],
//   datasets: [
//     {
//       label: "",
//       backgroundColor: gradient,
//       pointBackgroundColor: "#f44336",
//       borderWidth: 2,
//       pointRadius: 0,
//       borderColor: "#f44336",
//       data: [
//         300000,
//         2500000,
//         800000,
//         910000,
//         5400000,
//         5000000,
//         600000,
//         10000000,
//         5000000,
//         600000,
//         8000000,
//         1000000
//       ],
//       fill: true
//     }
//   ]
// };

// var options = {
//   responsive: true,
//   animation: {
//     easing: "easeInOutQuad",
//     duration: 520
//   },
//   hover: {
//     mode: mode,
//     intersect: false
//   },
//   scales: {
//     xAxes: [
//       {
//         gridLines: {
//           color: "transparent",
//           lineWidth: 0
//         }
//       }
//     ],
//     yAxes: [
//       {
//         ticks: {
//           min: 100000,
//           stepSize: 2000000,
//           callback: function(value, index, values) {
//             if (parseInt(value) >= 1000000) {
//               return (
//                 value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "m"
//               );
//             }
//             if (parseInt(value) >= 1000) {
//               return (
//                 value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "k"
//               );
//             } else {
//               return value;
//             }
//           }
//         },
//         gridLines: {
//           color: "transparent",
//           lineWidth: 0
//         }
//       }
//     ]
//   },
//   elements: {
//     line: {
//       tension: 0.4
//     }
//   },
//   legend: {
//     display: false
//   },
//   tooltips: {
//     titleFontFamily: "Open Sans",
//     backgroundColor: "rgba(0,0,0,0.3)",
//     titleFontColor: "red",
//     caretSize: 5,
//     cornerRadius: 2,
//     xPadding: 10,
//     yPadding: 10,
//     mode: mode,
//     intersect: false
//   },
//   layout: {
//     padding: {
//       left: 0,
//       right: 0,
//       top: 0,
//       bottom: 0
//     }
//   }
// };

// var chartInstance = new Chart(chart, {
//   type: "line",
//   data: data,
//   options: options
// });

// console.log("Home.js has been loaded");
// console.log(myip);

$(document).ready(function () {

  $("#userNickname").keyup(function (e) {
    $('#nickNo').hide();
    $('#spaceNo').hide();
    $('#nickYes').hide();
    $('#patternNo').hide();
    if (e.keyCode == 13) {
    $("#add").click();
    }
});

$("#add").click(function () {
  var valid = 0;
  var nick = $("#userNickname").val().toLowerCase();
      // Check for white space
      if (nick.indexOf(' ') >= 0) {
        $('#spaceNo').fadeIn();
        $('#patternNo').hide();
         valid = 0;
      }else {
          $('#spaceNo').hide();
          if (/^[a-zA-Z0-9-]+$/.test(nick)) {
            if(nick.length > 1)
            {
                $('#patternNo').hide();
                valid = 1;
            }
            else
            {
              $('#patternNo').fadeIn();
              valid = 0;
            }
          }
          else
          {
            $('#patternNo').fadeIn();
            valid = 0;
          }
      }

if(valid)
{
  // console.log(nick);

  $.ajax({
    type: "POST",
    url: '/checknick',
    data:{
      nick: nick
    },
    success: function(data){
      var nickCode = JSON.parse(data);
      console.log(nickCode);
      if(nickCode.code == '200')
      {
        $('#nickNo').hide();
        $('#nickYes').fadeIn();
      //window.location.href = "/dashboard";
      }else if(nickCode.code == '198')
      {
        $('#nickYes').hide();
        $('#nickNo').fadeIn();
      }
    //  console.log(loginCode);
      //alert('success'+JSON.stringify(data));
    },
    error: function(data){
      alert('failure'+JSON.stringify(data));
    }
  });
}
});

$("#confirmNick").click(function () {
  console.log("confirmed");
  var nick = $("#userNickname").val().toLowerCase();
  $.ajax({
    type: "POST",
    url: '/setnick',
    data:{
      nick: nick
    },
    success: function(data){
      var nickCode = JSON.parse(data);
      // console.log(nickCode);
      if(nickCode.code == '200')
      {
        window.location.href ='/dashboard';
      }
    },
    error: function(data){
      alert('failure'+JSON.stringify(data));
    }
  });
});


$("#allowLocation").click(function () {
  console.log("confirmed");
  console.log("Loading map...");
  $("#allowLocation").hide();
  $('#showProgress').fadeIn();
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
      lat = position.coords.latitude;
      lon = position.coords.longitude;
      console.log(lat+""+lon);

      $.ajax({
        type: "POST",
        url: '/store-my-location',
        data:{
          lat: lat,
          lng: lon
        },
        success: function(data){
          var reportCode = JSON.parse(data);
          // console.log(reportCode);
          if(reportCode.code == '200')
          {
            location.reload();
          }
        },
        error: function(data){
          alert('failure'+JSON.stringify(data));
        }
      });


}, function() {
      handleNoGeolocation(browserSupportFlag);
    });
  }
  // Browser doesn't support Geolocation
  else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }

});



var geocoder = new google.maps.Geocoder();
function geocoding(keyword) {
    geocoder.geocode({address: keyword}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) { // if got results

          var formattedAddress = results[0].formatted_address;
          var latFound =  results[0].geometry.location.lat();
          var lonFound =  results[0].geometry.location.lng();
          // console.log(formattedAddress);
          //   console.log(latFound);
          //     console.log(lonFound);
              var latlng = new google.maps.LatLng(latFound, lonFound);
              var mapOptions = {
                  zoom: 8, // set the map zoom level
                  // center: latlng, // set the center region
                  disableDefaultUI: true,
                  zoomControl: true, // whether to show the zoom control beside
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              };
              var map = new google.maps.Map(document.getElementById('mapme'), mapOptions);
              // ADD THIS
              var marker = new google.maps.Marker({
                  map: map, // refer to the map you've just initialise
                  // position: latlng, // set the marker position (is based on latitude & longitude)
                  draggable: true // allow user to drag the marker
              });
            // always take the first result only
            map.setCenter(results[0].geometry.location); // set the map region to center
            marker.setPosition(results[0].geometry.location); // change the marker position
            google.maps.event.addListener(marker, 'dragend', function() {
                // it will run this only if user DROP the marker down (drag end)
                var position = marker.getPosition();
                var currentLat = position.lat();
                var currentLon = position.lng();
                  console.log("Lat"+currentLat + " " + "Long"+currentLon);
                    var latlng = new google.maps.LatLng(position.lat(), position.lng());
                    getAddressNow(latlng);
      });


        } else {

        }
    });
}

function getAddressNow(latlng)
{
  $( "#enterAddressInput" ).fadeOut();
  $("#reportNotification").fadeOut();
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      var formattedAddress = results[0].formatted_address;
      appendDynamic(formattedAddress,latlng);
         console.log(formattedAddress);
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

function appendDynamic(formattedAddress,latlng)
{
  console.log("fullAddress"+JSON.stringify(latlng.H));
  $( "#foundAddressPane" ).fadeIn();
  $( "#crimeDescPane" ).fadeIn();
  $( "#dynamicAddress" ).html('');
  $( "#dynamicAddress" ).append( formattedAddress );

  $( "#dynamicLat" ).html('');
    $( "#dynamicLng" ).html('');

    $( "#dynamicLat" ).append( latlng.H );
      $( "#dynamicLng" ).append( latlng.L );
}

$("#getAddress").click(function () {

  if($("#searchAddress").val()){
    $( "#enterAddressInput" ).fadeOut();
    var address= $("#searchAddress").val();
    geocoding(address); // get the value from address text box and pass to the search function
}
else {
  $( "#enterAddressInput" ).fadeIn();
}

});

$("#searchAddress").keyup(function (e){
  if($("#searchAddress").val()){
  if (e.keyCode == 13) {
    $("#getAddress").click();
  }
}
});


$("#sendCrimeReport").click(function () {

    if($("#cdT").val()){
        $("#descriptionTextError").fadeOut();

        var crimeDesc = $("#cdT").val();
        var rID =  $("#reporterID").html();
        var lat = $( "#dynamicLat" ).html();
        var lng =  $( "#dynamicLng" ).html();
        var formattedAddress = $("#dynamicAddress").html();

        console.log(lat);
          console.log(lng);
            console.log(formattedAddress);
              console.log(crimeDesc);
        //ajax hit to save the crime report-crime
          // var searchedAddress = $("#reqRecovery").val().toLowerCase();
          $.ajax({
            type: "POST",
            url: '/report-this-crime',
            data:{
              userId: rID,
              lat: lat,
              lng: lng,
              formattedAddress: formattedAddress,
              crimeDesc: crimeDesc,
              myip:myip
            },
            success: function(data){
              var reportCode = JSON.parse(data);
              // console.log(reportCode);
              if(reportCode.code == '200')
              {
                $("#cdT").val('');
                $("#crimeDescPane").hide();
                $( "#repId" ).html('');
                 $( "#repId" ).append(reportCode.data);
                   $("#reportNotification").show().delay(5000).fadeOut();
              }
            },
            error: function(data){
              alert('failure'+JSON.stringify(data));
            }
          });
        //
}
else {
      $("#cdT").focus();
      $("#descriptionTextError").fadeIn();
}
});

});




function loadReportCrimeMap()
{

  var geocoder = new google.maps.Geocoder();
  function geocoding(keyword) {
      geocoder.geocode({address: keyword}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) { // if got results

            var formattedAddress = results[0].formatted_address;
            var latFound =  results[0].geometry.location.lat();
            var lonFound =  results[0].geometry.location.lng();
            // console.log(formattedAddress);
            //   console.log(latFound);
            //     console.log(lonFound);
                var latlng = new google.maps.LatLng(latFound, lonFound);
                var mapOptions = {
                    zoom: 8, // set the map zoom level
                    // center: latlng, // set the center region
                    disableDefaultUI: true,
                    zoomControl: true, // whether to show the zoom control beside
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById('mapme'), mapOptions);
                // ADD THIS
                var marker = new google.maps.Marker({
                    map: map, // refer to the map you've just initialise
                    // position: latlng, // set the marker position (is based on latitude & longitude)
                    draggable: true // allow user to drag the marker
                });
              // always take the first result only
              map.setCenter(results[0].geometry.location); // set the map region to center
              marker.setPosition(results[0].geometry.location); // change the marker position
              google.maps.event.addListener(marker, 'dragend', function() {
                  // it will run this only if user DROP the marker down (drag end)
                  var position = marker.getPosition();
                  var currentLat = position.lat();
                  var currentLon = position.lng();
                    console.log("Lat"+currentLat + " " + "Long"+currentLon);
                      var latlng = new google.maps.LatLng(position.lat(), position.lng());
                      getAddressNow(latlng);
        });


          } else {

          }
      });
  }

  // function loadMapNow()
  // {
    console.log("Loading map...");
    if(navigator.geolocation) {
      browserSupportFlag = true;
      navigator.geolocation.getCurrentPosition(function(position) {
        lat = position.coords.latitude;
        lon = position.coords.longitude;
        var latlng = new google.maps.LatLng(lat, lon);
        var mapOptions = {
            zoom: 8, // set the map zoom level
            center: latlng, // set the center region
            disableDefaultUI: true,
            zoomControl: true, // whether to show the zoom control beside
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('mapme'), mapOptions);
        var marker = new google.maps.Marker({
            map: map, // refer to the map you've just initialise
            position: latlng, // set the marker position (is based on latitude & longitude)
            draggable: true // allow user to drag the marker
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            // it will run this only if user DROP the marker down (drag end)
            var position = marker.getPosition();
            var currentLat = position.lat();
            var currentLon = position.lng();
            var latlng = new google.maps.LatLng(position.lat(), position.lng());
            getAddressNow(latlng);
  });
  }, function() {
        handleNoGeolocation(browserSupportFlag);
      });
    }
    // Browser doesn't support Geolocation
    else {
      browserSupportFlag = false;
      handleNoGeolocation(browserSupportFlag);
    }

  // }

  function getAddressNow(latlng)
  {
    $( "#enterAddressInput" ).fadeOut();
    $("#reportNotification").fadeOut();
    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK) {
        var formattedAddress = results[0].formatted_address;
        appendDynamic(formattedAddress,latlng);
           console.log(formattedAddress);
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });
  }

  function appendDynamic(formattedAddress,latlng)
  {
    console.log("fullAddress"+JSON.stringify(latlng.H));
    $( "#foundAddressPane" ).fadeIn();
    $( "#crimeDescPane" ).fadeIn();
    $( "#dynamicAddress" ).html('');
    $( "#dynamicAddress" ).append( formattedAddress );

    $( "#dynamicLat" ).html('');
      $( "#dynamicLng" ).html('');

      $( "#dynamicLat" ).append( latlng.H );
        $( "#dynamicLng" ).append( latlng.L );
  }
}
//below document on ready
function loadNearByCrimesNow(nickname,userLat,userLon,crimeData)
{
  console.log("Current Users nickname" + nickname);
  console.log("Current Users Lat" + userLat);
  console.log("Current Users Lon" + userLon);
  console.log("Shall i load ?" + crimeData.length);
        var map;
        callMyMap(userLat,userLon);
        function callMyMap(lat,lon)
        {
          console.log("lat"+lat+"--------lon "+lon);
          map = new GMaps({
            div: '#aggregatedMap',
            lat: lat,
            lng: lon,
            markerClusterer: function(map) {
              return new MarkerClusterer(map);
            }
          });

                      map.addMarker({
                        lat: lat,
                        lng: lon,
                        title: nickname,
                          infoWindow: {
                            content: '<p>You are here !</p>'
                          }
                      });

          for(var i = 0; i < crimeData.length; i++)

{

            var latitude = crimeData[i].lat;
            var longitude = crimeData[i].lon;
              console.log("lat"+latitude+"--------lon "+longitude)

            map.addMarker({
              lat: latitude,
              lng: longitude,
              infoWindow: {
                content: '<p>Report ID: '+crimeData[i].reportId+'</p><p>Reported by: '+crimeData[i].nickname+'</p><p><a href="#" onclick="reportFunction('+crimeData[i].reportId+');">Show Report</p>'
              }
            });
          }
}



} //loadNearByCrimesNow ends

//function to get the report description
function reportFunction(reportId)
{
  console.log("reportFunction"+reportId);
  $.ajax({
    type: "POST",
    url: '/get-report-details',
    data:{
      reportId: reportId
    },
    success: function(data){
      var reportCode = JSON.parse(data);
      console.log(reportCode);
      if(reportCode.code == '200')
      {
                console.log(reportCode.data.nickname);
                var formattedDate = moment(reportCode.data.reportedOn, "YYYYMMDD").fromNow();
                $( "#showAlert" ).hide();
                $( "#displayReports" ).hide();
                $(".reportDetails").html('');
                $( "#nicknameR" ).html(reportCode.data.nickname);
                $( "#reportedOnR" ).html('Reported '+formattedDate);
                $( "#reportIdR" ).html(reportCode.data.reportId);
                $( "#latR" ).html(reportCode.data.lat);
                $( "#lonR" ).html(reportCode.data.lon);
                $( "#fullAddressR" ).html(reportCode.data.address);
                $( "#fullDescriptionR" ).html(reportCode.data.description);
                $( "#displayReports" ).show();
      }
    },
    error: function(data){
      alert('failure'+JSON.stringify(data));
    }
  });
}

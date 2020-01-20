jQuery(document).ready(function($) {
  var myStorage = window.localStorage;
  var outputTarget = $("#output");
  var lists = getAllListsInStorage()['lists'], 
      foundChecklists = getAllListsInStorage()['found'];

  var postID = $("main > article").attr("id");
  postID = postID !== undefined ? postID.split("-")[1] : null;

  if( $(".sk-checklist").length ) {
    $(".sk-checklist").each(function() {
      var thisList = $(this);
      var items = [];
      thisList.children("li").each(function() {
        items.push( $(this).html() );
      });

      if( foundChecklists ) {
        $.each(lists, function(k, v) {
          var json = JSON.parse(lists[k]);
          var id = json['checklist_id'];

          var keys = Object.keys(json);
          keys.shift();

          if( arraysEqual(keys, items) ) {
            console.log("BUILDING List ...");
            buildChecklist(thisList, json);

          } else {
            console.log("CREATING List ...");
            createChecklist(thisList);
          }
        });
      } else {
        console.log("CREATING List ...");
        createChecklist( $(this) );
      }
    });
  }

  function createChecklist(el) {

    id = getRandomInt(0,9999) +"-"+ postID;

    var checklist = checklistButtons(id);

    $(".sk-checklist li").each(function() {
      var name = $(this).html();

      checklist += "<li class='sk-checklist-item'><input type='checkbox' name='"+ name +"' value='"+ name +"' >"+ name + "<i class='fas fa-check hidden'></i></li>";
    

    });
    checklist += "</ul></div>";

    el.hide();
    el.after(checklist);

    defineClicks();
  }

  function buildChecklist(el,json) {
    var id = json['checklist_id'];
    delete json['checklist_id'];

    el.hide();

    var checklist = checklistButtons(id);
    $.each(json, function(i, v) {
      var checked = v === "complete" ? "checked" : "";
      var hidden = v === "complete" ? "" : "hidden";
      checklist += "<li class='sk-checklist-item "+ checked +"'><input type='checkbox' name='"+ i +"' value='"+ i +"' "+ checked +">"+ i +" <i class='fas fa-check "+ hidden +"'></i></li>";
    });
    checklist += "</ul></div>";

    el.after(checklist);

    saveChecklist(id);

    defineClicks();
  }

  function checklistButtons(id) {
    var list_id = id.split("-")[0];
    var checklist = "<div class='sk-checklist-container'>";
    checklist += "<h3 class='sk-checklist-title'>Checklist ["+ list_id +"]</h3>";
    checklist += " <div class='button-container'><input type='button' name='clear-checklist' class='clear-checklist' value='Clear' id='clear-checklist-"+ id +"' data-checklist-id='"+ id +"'><span class='cert-check' style='display: none;'><i class='fas fa-check'></i><i class='fas fa-certificate'></i></span></div>";
    if($("body").hasClass("logged-in")) {
      // checklist += "<div class='button-container'><input type='button' name='save-checklist' class='save-checklist' value='Save Checklist' id='save-checklist-"+ id +"' data-checklist-id='"+ id +"'><span class='cert-check' style='display: none;'><i class='fas fa-check'></i><i class='fas fa-certificate'></i></span></div>";

      // checklist += " <div class='button-container'><input type='button' name='remove-checklist' class='remove-checklist' value='Remove Checklist' id='remove-checklist-"+ id +"' data-checklist-id='"+ id +"'><span class='cert-check' style='display: none;'><i class='fas fa-check'></i><i class='fas fa-certificate'></i></span></div>";

      // checklist += " <div class='button-container'><input type='button' name='clear-storage' class='clear-storage' value='Clear Storage'><span class='cert-check' style='display: none;'><i class='fas fa-check'></i><i class='fas fa-certificate'></i></span></div>";
    }

    checklist += "<ul id='sk-checklist-"+ id +"' class='sk-checklist' data-checklist-id='"+ id +"'>";

    return checklist;
  }

  function defineClicks() {
    $(".sk-checklist-item").click(function() {
      var id = $(this).parent("ul").data('checklist-id');
      var item = $(this).children("input");

      item.parent("li").toggleClass('checked');
      item.siblings("i").toggleClass('hidden');

      if( item.parent("li").hasClass("checked") ) {
        item.attr('checked', true);

      } else {
        item.attr('checked', false);

      }

      if( foundChecklists && lists["sk-checklist-"+ id] ) {
        updateStorage(id, item.attr("name"));

      } else {
        saveChecklist(id);

      }
    });

    $(".save-checklist").click(function() {
      var id = $(this).data('checklist-id');

      var saved = saveChecklist(id);
      if(saved) {
        $(this).siblings("span").fadeIn('fast').delay(300).fadeOut();
      }
    });

    $(".clear-checklist").click(function() {
      var id = $(this).data('checklist-id');

      var cleared = clearChecklist(id);
      if(cleared) {
        $(this).siblings("span").fadeIn('fast').delay(300).fadeOut();
      }
    });

    $(".remove-checklist").click(function() {
      var id = $(this).data('checklist-id');

      var removed = removeChecklist(id);
      if(removed) {
        $(this).siblings("span").fadeIn('fast').delay(300).fadeOut();
      }
    });

    $(".clear-storage").click(function() {
      clearStorage();

      $(this).siblings("span").fadeIn('fast').delay(300).fadeOut();
    });
  }

  function updateStorage(id, key) {
    var complete = $("input[name='"+ key +"']").parent("li").hasClass("checked") ? "complete" : "";
    var list = myStorage.getItem("sk-checklist-"+ id);
    var json = JSON.parse(list);

    json[key] = complete;
    json['checklist_id'] = id;

    jsonStr = JSON.stringify(json);
    myStorage.setItem("sk-checklist-"+ id, jsonStr);

    console.log("updated checklist #"+ id);
  }

  function saveChecklist(id) {
    var items = {'checklist_id': id};

    $("#sk-checklist-"+ id +" input").each(function() {
      items[$(this).val()] = $(this).is(":checked") ? "complete" : "";
    });

    jsonStr = JSON.stringify(items);
    json = JSON.parse(jsonStr);
    json['post_id'] = postID;

    myStorage.setItem('sk-checklist-'+ id, jsonStr);

    // jQuery.ajax({
    //   url: ajaxChecklistsObject.checklists_ajax_url, // this is the object instantiated in wp_localize_script function
    //   type: 'POST',
    //   data: json,
    //   success: function(data) {
    //     console.log("success!");
    //     console.log(data);
    //   },
    //   error: function(data) {
    //     console.log("::ERROR::");
    //     console.log(data);
    //   }
    // });

    if( getChecklist(id) ) {
      return true;

    } else {
      return false;

    }
  }

  function getChecklist(id) {
    return myStorage.getItem('sk-checklist-'+ id);
  }

  function clearChecklist(id) {
    $("#sk-checklist-"+ id +" input").each(function() {
      $(this).prop('checked', false).siblings("i").addClass('hidden').parents("li").removeClass('checked');
    });

    var storageList = myStorage.getItem("sk-checklist-"+ id);
    var json = JSON.parse(storageList);
    $.each(json, function(k, v) {
      json[k] = "";
    });

    json['checklist_id'] = id;

    var jsonStr = JSON.stringify(json);
    myStorage.setItem("sk-checklist-"+ id, jsonStr);

    return true;
  }

  function removeChecklist(id) {
    myStorage.removeItem('sk-checklist-'+ id);

    $("#sk-checklist-"+ id +" input").each(function() {
      $(this).prop('checked', false).siblings("i").addClass('hidden').parents("li").removeClass('checked');
    });

    if( getChecklist(id) ) {
      return false;

    } else {
      return true;

    }
  }

  function clearStorage() {
    myStorage.clear();

    $(".sk-checklist-item").each(function() {
      $(this).removeClass('checked');
      $(this).children("input").attr("checked", false);
      $(this).children("i").addClass("hidden");
    });
  }

  function getAllListsInStorage() {
    var post_id = $("main > article").attr("id");
    
    if(post_id !== undefined) {
      post_id = post_id.split("-")[1];

      var values = {},
          keys = Object.keys(localStorage),
          i = keys.length;

      while ( i-- ) {
        values[keys[i]] = localStorage.getItem(keys[i]);
      }

      $.each(values, function(k,v) {
        if( k.includes("sk-checklist") && k.includes(post_id) ) {
          foundChecklists = true;

        } else {
          delete values[k];

        }
      });

      var results = {'lists': values, 'found': foundChecklists};
      return results;
    }
    
    return {};
  }

  function getRandomInt(min=0,max=100) {
    return Math.floor(Math.random(min) * Math.floor(max));
  }

  // https://stackoverflow.com/a/16436975
  function arraysEqual(a, b) {
    if (a === b) return true;
    if (a == null || b == null) return false;
    if (a.length != b.length) return false;

    // If you don't care about the order of the elements inside
    // the array, you should sort both arrays here.
    // Please note that calling sort on an array will modify that array.
    // you might want to clone your array first.

    for (var i = 0; i < a.length; ++i) {
      if (a[i] !== b[i]) return false;
    }
    return true;
  }
  
});
jQuery(document).ready(function($) {
  var myStorage = window.localStorage;
  var outputTarget = $("#output");
  var list = myStorage.getItem('checklist') !== '' ? myStorage.getItem('checklist') : 'none';

  getChecklist();

  if( $(".sk-checklist").length ) {
    var id = null;

    $(".sk-checklist").each(function() {
      var classes = $(this).attr("class");
      if( !classes.includes("sk-list") ) {
        id = "sk-list-"+ getRandomInt(0,9999);
        $(this).addClass(id);

        $("."+ id).before("<input type='button' name='save-checklist' class='save-checklist' value='Save Checklist (Local Storage)' id='save-checklist-"+ id +"' data-checklist-id='"+ id +"'> <input type='button' name='clear-checklist' class='clear-checklist' value='Clear Checklist' id='clear-checklist-"+ id +"' data-checklist-id='"+ id +"'> <input type='button' name='clear-storage' class='clear-storage' value='Clear Storage'>");
      }

      $(".save-checklist").click(function() {
        var id = $(this).data("checklist-id");
        id = id.split("-");
        id = id[2];
        console.log(id);

        saveChecklist(id);
      });

      $(".clear-checklist").click(function() {
        var id = $(this).data("checklist-id");
        id = id.split("-");
        id = id[2];

        removeChecklist(id);

        $(".sk-checklist-item").each(function() {
          $(this).removeClass('checked');
          $(this).children("input").attr("checked", false);
          $(this).children("i").addClass("hidden");

        });
      });

      $(".clear-storage").click(function() {
        clearStorage();
      });

    });

    $(".sk-checklist li").each(function() {
      var name = $(this).html();
      $(this).addClass('sk-checklist-item');
      $(this).prepend("<input type='checkbox' name='"+ name +"' value='"+ name +"' >");
      $(this).append("<i class='fas fa-check hidden'></i>");

      $(this).click(function() {
        toggleCheck($(this).children("input"));
      });
    });
  }

  function toggleCheck(item) {
    item.parent("li").toggleClass('checked');
    item.siblings("i").toggleClass('hidden');
  }

  function saveChecklist(id) {
    var items = {'checklist-id': id}, n = 0;
    $(".checked input").each(function() {
      items[n] = $(this).val();
      n++;
    });

    itemsStr = JSON.stringify(items);

    myStorage.setItem('sk-checklist-'+ id, itemsStr);
  }

  function getChecklist(id) {
    return myStorage.getItem('sk-checklist-'+ id);
  }

  function removeChecklist(id) {
    myStorage.removeItem('sk-checklist-'+ id);
  }

  function clearStorage() {
    myStorage.clear();
  }

  function getRandomInt(min=0,max=100) {
    return Math.floor(Math.random(min) * Math.floor(max));
  }
  
});
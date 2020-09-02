
var canv = document.getElementById("canv");
var ctx  = canv.getContext('2d');
ctx.width = 768;
ctx.height = 480;
var points = [];
var i = 0;
write_elements(ctx);

//draw point on canvas
function draw_point(i, x, y, ctx)
{
    var circle = new Path2D();
    ctx.fillStyle="#fff";
    circle.arc(x, y, 16, 0, 2 * Math.PI); // white circle
    ctx.fill(circle);
    ctx.fillStyle="#000";
    ctx.stroke(circle);
    ctx.font = "18px Arial";
    if (i < 10) //black text on circle
        ctx.fillText(i, x-5, y+7);
    else if (i < 100)
        ctx.fillText(i, x-10, y+7);
    else
        ctx.fillText(i, x-15, y+7);
}

//draw cells in tablerow
function create_input_cell(input_name, input_value, input_style)
{
    
    let elem = document.createElement("td");
    let inp = document.createElement("input");
    inp.name = input_name;
    inp.value = input_value;
    inp.type = input_style;
    if (inp.type === "hidden")
        return (inp);
    elem.appendChild(inp);
    return(elem);
}
//draw new row in table
function create_input_string(name_array, value_array, style_array)
{
    let elem = document.createElement("tr");
    for (let i = 0; i < name_array.length; i++) 
        elem.appendChild(create_input_cell(name_array[i], value_array[i], style_array[i]));   
    return elem;
}

// draw on canvas new points
function getMousePosition(canvas, event, ctx) 
{ 
    let rect = canvas.getBoundingClientRect(); 
    let x = event.clientX - rect.left; 
    let y = event.clientY - rect.top; 
    i++;
    draw_point(i, x, y, ctx);
    read_list();
    var form = document.getElementById("point-form");
    form.append(create_input_string(["delete[]","point[]", "id[]", "x-coord[]", "y-coord[]", "desc[]"], [i, i, 0, x, y, ""], ["checkbox","text", "hidden", "hidden", "hidden", "text"]));
} 

//read current table to array of objects
function read_list()
{
    let opt = document.getElementsByName("delete[]");
    let points = document.getElementsByName("point[]");
    let ids = document.getElementsByName("id[]");
    let xpoint = document.getElementsByName("x-coord[]");
    let ypoint = document.getElementsByName("y-coord[]");
    let desk = document.getElementsByName("desc[]"); 
    let data = [];
    for (let index = 0; index < points.length; ++index) 
    {
        data.push({
            "option" : opt[index].checked,
            "pnt" : points[index].value,
            "ids" : ids[index].value,
            "x" : xpoint[index].value,
            "y" : ypoint[index].value,
            "desc" : desk[index].value
        });
    }
    return (data);
}

// draw current table on canvas
function write_elements(ctx)
{
    let data = read_list();
    i = data.length;
    for (let index = 0; index < data.length; index++)
        draw_point(data[index].pnt, parseInt(data[index].x), parseInt(data[index].y), ctx); 
} 
// read clicks on canvas
canv.addEventListener("mousedown", function(e) 
        { 
            getMousePosition(canv, e, ctx); 
        }); 

function del_str()
{
    let data = read_list();
    data = data.filter(el => el.option == false);
    for (let index = 0; index < data.length; index++) 
        data[index].pnt = index + 1;
    ctx.clearRect(0, 0, ctx.width, ctx.height);
    let table = document.getElementById("point-form");
    clearTable(table);
    i = data.length;
    for (let index = 0; index < i; index++) 
    {
        draw_point(data[index].pnt, parseInt(data[index].x), parseInt(data[index].y), ctx);
        table.append(create_input_string(["delete[]","point[]", "id[]", "x-coord[]", "y-coord[]", "desc[]"], [index + 1, index + 1, data[index].ids, data[index].x, data[index].y, data[index].desc], ["checkbox","text", "hidden", "hidden", "hidden", "text"]));
    }
}

function clearTable(table) {
    var rows = table.rows;
    var i = rows.length;
    while (--i)
      rows[i].parentNode.removeChild(rows[i]);
  }


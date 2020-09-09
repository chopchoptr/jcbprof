var canv = document.getElementById("canv-1");
var ctx  = canv.getContext('2d');
ctx.width = 768;
ctx.height = 480;
var points = [];
var i = 0;
write_elements(ctx);

function get_point_data(i)
{
    let point = document.getElementsByName("point[]")[i]; 
    let xpoint = document.getElementsByName("x-coord[]")[i]; 
    let ypoint = document.getElementsByName("y-coord[]")[i]; 
    return ({
        "num"   :   point.value,
        "x"     :   parseInt(xpoint.value),
        "y"     :   parseInt(ypoint.value)
    });
}

function activate_point(i)
{
    write_elements(ctx);
    var table = document.getElementById("point-form");
    let data = get_point_data(i);
    var circle = new Path2D();
    ctx.fillStyle="#f1ac06";
    circle.arc(data.x, data.y, 12, 0, 2 * Math.PI);
    ctx.fill(circle);
    ctx.fillStyle="#000";
    ctx.stroke(circle);
    ctx.font = "14px Arial";
    if (i < 10)
        ctx.fillText(data.num, data.x-4, data.y+5);
    else if (i < 100) 
        ctx.fillText(data.num, data.x-8, data.y+5);
    else 
        ctx.fillText(data.num, data.x-12, data.y+5);
}

function draw_point(i, x, y, ctx)
{
    var circle = new Path2D();
    ctx.fillStyle="#fff";
    circle.arc(x, y, 12, 0, 2 * Math.PI);
    ctx.fill(circle);
    ctx.fillStyle="#000";
    ctx.stroke(circle);
    ctx.font = "14px Arial";
    if (i < 10)
        ctx.fillText(i, x-4, y+5);
    else if (i < 100) 
        ctx.fillText(i, x-8, y+5);
    else 
        ctx.fillText(i, x-12, y+5);
}

function getMousePosition(canvas, event, ctx)
{
    var table = document.getElementById("point-form");
    let i = table.rows.length; 
    while (--i) 
        table.rows[i].classList.remove("active-line");
    let rect = canvas.getBoundingClientRect();
    let x = event.clientX - rect.left;
    let y = event.clientY - rect.top;
    let data = read_list();
    var table = document.getElementById("point-form");
    let min_index = 0;
    let min_dist = -1;
    for (let index = 0; index < data.length; index++)
    {
        if (min_dist < 0 || (parseInt(x) - parseInt(data[index].x))**2+(parseInt(y) - parseInt(data[index].y))**2 < min_dist)
        {
            min_index = data[index].pnt;
            min_dist = (parseInt(x) -  parseInt(data[index].x))**2+(parseInt(y) - parseInt(data[index].y))**2;
        }
    } 
    activate_point(min_index - 1);
    table.rows[min_index].classList.add("active-line");
    $(".active-line").scrollTop(60);
} 

function read_list()
{
    let opt = document.getElementsByName("delete[]"); 
    let points = document.getElementsByName("point[]"); 
    let xpoint = document.getElementsByName("x-coord[]"); 
    let ypoint = document.getElementsByName("y-coord[]"); 
    let desc = document.getElementsByName("desc[]"); 
    let data = []; 
    for (let index = 0; index < points.length; ++index)
    {
        data.push({
            "pnt" : points[index].value,
            "x" : xpoint[index].value,
            "y" : ypoint[index].value,
            "desc" : desc[index].value
            });
    }
    return(data);
} 

function write_elements(ctx)
{ 
    let data = read_list(); 
    i = data.length; 
    for (let index = 0; index < data.length; index++) 
        draw_point(data[index].pnt, parseInt(data[index].x), parseInt(data[index].y), ctx);
} 

canv.addEventListener("mousedown", function(e){getMousePosition(canv, e, ctx);}); 

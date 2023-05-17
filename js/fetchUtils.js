"use strict";
  /*
 *   get query string from FormData object
 *   fd : FormData instance
 *   returns : query string with fd parameters (without initial '?')
 */
function formDataToQueryString (fd){
  return Array.from(fd).map(function(p){return encodeURIComponent(p[0])+'='+encodeURIComponent(p[1]);}).join('&');
}

{ 
  let makeFetchFunction = function (type){
    let processResponse = function(response){ if (response.ok) return response[type](); throw Error(response.statusText); };
    return function(...args){ return fetch(...args).then(processResponse); };
  };
  /*
   *   Fetch functions :
   *      same arguments as fetch()
   *      each function returns a Promise resolving as received data
   *      each function throws an Error if not response.ok
   *   fetchText : returns Promise resolving as received data, as string
   *   fetchObject : returns Promise resolving as received data, as object (from JSON data)
   *   fetchFromJson : fetchFromObject alias
   *   fetchBlob : returns Promise resolving as received data, as Blob
   *     ...
   */
  var fetchObject = makeFetchFunction('json');
  var fetchFromJson = fetchObject;
  var fetchBlob = makeFetchFunction('blob');
  var fetchText = makeFetchFunction('text');
  var fetchArrayBuffer = makeFetchFunction('arrayBuffer');
  var fetchFormData = makeFetchFunction('formData');
}

function processAnswer(answer){
  if(answer.status=='ok')
    return answer.result;
  else  
    throw new Error(answer.message);
}

function listToTable(list){
  let table = document.createElement('table');
  let row = table.createTHead().insertRow();
  for (let x of Object.keys(list[0]))
    row.insertCell().textContent = x;
  let body = table.createTBody();
  for (let line of list){
    let row = body.insertRow();
    for (let x of Object.values(line))
      row.insertCell().textContent = x;
  }
  return table;
}


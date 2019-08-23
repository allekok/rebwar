/* Constants */
const wordlists = {
    ckb: 'کوردیی ناوەندی',
};
const wordlists_selected_storage_name = 'wordlists_selected';
const wordlists_selected_storage = isJSON(
    localStorage.getItem(wordlists_selected_storage_name));
const wordlists_selected = wordlists_selected_storage ||
      [ 'ckb' ];
const wordlists_el_id = 'wordlists';
const q_el_id = 'q';
const result_el_id = 'result';
const form_el_id = 'frm';

/* Functions */
function getUrl (url, callback)
{
    const client = new XMLHttpRequest();
    client.open('get', url);
    client.onload = function ()
    {
	callback(this.responseText);
    }
    client.send();
}

function postUrl (url, request, callback)
{
    const client = new XMLHttpRequest();
    client.open('post', url);
    client.onload = function ()
    {
	callback(this.responseText);
    }
    client.setRequestHeader(
	"Content-type","application/x-www-form-urlencoded");
    client.send(request);
}

function lookup ()
{
    const q_el = document.getElementById(q_el_id);
    const wordlists = get_selected_wordlists();
    const wordlists_req = wordlists.join(',');
    const result_el = document.getElementById(result_el_id);
    const q = encodeURIComponent(q_el.innerText.trim());
    const url = 'src/backend/spellcheck.php';
    const request = `q=${q}&wordlists=${wordlists_req}&output=json`;
    const loading = '<div class="loading"></div>';

    if(!q)
    {
	q_el.focus();
	return;
    }
    if(wordlists.length == 0)
    {
	result_el.innerHTML = '<p>(تکایە وشەنامەیەک هەڵبژێرن)</p>';
	return;
    }

    // Loading animation
    result_el.innerHTML = loading;

    // Save selected wordlists
    save_selected_wordlists(wordlists);
    
    postUrl(url, request, function(response) {
	response = isJSON(response);
	
	let toprint = '<p>گەڕان ' + response.time
	    + 'چرکەی خایاند.</p>';
	let q_new = q_el.innerHTML.replace(
	    /<span class="wrong">/gi, '').
	    replace(/<\/span>/gi, '');
	let all_true = true;
	
	for(const w in response)
	{
	    if(w == 'time') continue;
	    
	    q_new = q_new.replace(
		new RegExp(w, 'g'),
		`<span class='wrong'>${w}</span>`);
	    all_true = false;
	}

	if(all_true)
	{
	    toprint += '<p><i class="ok">(هیچ هەڵەیەک بەدی نەکرا)</i></p>';
	}
	
	q_el.innerHTML = q_new;
	result_el.innerHTML = toprint;
    });
}

function isJSON (string)
{
    try
    {
	return JSON.parse(string);
    }
    catch (e)
    {
	console.log(e);
	return false;
    }
}

function get_selected_wordlists ()
{
    let selected = [];
    
    const wordlists_el = document.getElementById(wordlists_el_id);
    const wordlists_checks = wordlists_el.querySelectorAll('input[type=checkbox]');
    wordlists_checks.forEach(function (o) {
	const d = o.id;
	if(o.checked && wordlist_valid(d))
	    selected.push(d);
    });
    
    return selected;
}

function save_selected_wordlists (selected_wordlists)
{
    localStorage.setItem(wordlists_selected_storage_name,
			 JSON.stringify(selected_wordlists));
}

function wordlist_to_kurdish (wordlist)
{
    wordlist = wordlist.toLowerCase();
    try
    {
	return wordlists[wordlist];
    }
    catch (e)
    {
	console.log(e);
	return false;
    }
}

function wordlists_print ()
{
    let wordlists_html = '';
    for (const i in wordlists)
    {
	wordlists_html += `<div><input type="checkbox" id="${i}" 
${wordlists_selected.indexOf(i) !== -1 ? 'checked' : ''}
><label for="${i}">${wordlists[i]}</label></div>`;
    }
    document.getElementById(wordlists_el_id).innerHTML = wordlists_html;
}

function wordlist_valid (wordlist)
{
    for(const i in wordlists)
	if(i == wordlist) return wordlist;
    
    return false;
}

function clear_screen ()
{
    const result_el = document.getElementById(result_el_id);
    const q_el = document.getElementById(q_el_id);
    
    result_el.innerHTML = '';
    q_el.innerHTML = '';

    q_el.focus();
}

/* Events */
window.addEventListener('load', function () {
    // Wordlists
    wordlists_print();

    // Form
    const form_el = document.getElementById(form_el_id);
    form_el.addEventListener('submit', function(e) {
	e.preventDefault();
	lookup();
    });

    // Header
    const header_h1_el = document.querySelector('header h1');
    header_h1_el.addEventListener('click', function() {
	clear_screen();
    });
});

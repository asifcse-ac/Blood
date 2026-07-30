// sendAI: posts prompts/actions to an AI endpoint and renders results
function sendAI(url, prompt, targetSelector, options) {
  var target = document.querySelector(targetSelector);
  if (!target) return;
  target.textContent = 'Thinking...';
  options = options || {};
  var params = new URLSearchParams();
  params.append('prompt', prompt || '');
  params.append('text', prompt || '');
  if (options.action) params.append('action', options.action);

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString(),
    credentials:'include',
  }).then(function(res) {
    if (!res.ok) throw new Error('Network response was not ok');
    return res.json();
  }).then(function(data) {
    if (data.reply) target.textContent = data.reply;
    else if (data.result) target.textContent = data.result;
    else target.textContent = JSON.stringify(data, null, 2);
  }).catch(function(err) {
    target.textContent = 'Error: ' + err.message;
  });
}


if (typeof window !== 'undefined') window.sendAI = sendAI;

// Provide a small AI utility object with mock helpers for offline use.
(function(window){
    window.AI = {
        generateMessage: function({donorName, bloodGroup, urgency, hospital}){
            return new Promise((resolve) => {
                setTimeout(() => {
                    const msg = `Hello ${donorName},\n\nI hope you are well. We urgently need ${bloodGroup} blood for a patient at ${hospital}. The situation is ${urgency.toLowerCase()}. If you are available to donate, please reply or call the hospital. Thank you for your life-saving support!\n\n— Blood Bank Team`;
                    resolve(msg);
                }, 600);
            });
        },

        recommendDonors: function({bloodGroup, unitsRequested}){
            return new Promise((resolve) => {
                setTimeout(() => {
                    const recs = [
                        {name: 'Md. Karim', blood_group: bloodGroup || 'O+', distance_km: 2.1, phone: '9876543210'},
                        {name: 'Rashik', blood_group: bloodGroup || 'A+', distance_km: 3.7, phone: '9876501234'},
                        {name: 'Sadia', blood_group: bloodGroup || 'B+', distance_km: 5.2, phone: '9876512345'}
                    ];
                    resolve(recs.slice(0, Math.min(recs.length, 5)));
                }, 400);
            });
        },

        summarizeRequests: function(requests){
            return new Promise((resolve) => {
                setTimeout(() => {
                    const total = requests.length || 0;
                    const groups = {};
                    (requests||[]).forEach(r => { groups[r.blood_group] = (groups[r.blood_group] || 0) + 1; });
                    let lines = [`Total recent requests: ${total}`];
                    for (const g in groups) lines.push(`${g}: ${groups[g]} request(s)`);
                    resolve(lines.join('\n'));
                }, 300);
            });
        }
    };
})(window);

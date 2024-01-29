$(document).ready(function () {
  'use strict';
  // disqus init
  if (typeof shortName !== 'undefined') {
    let d = document,
      s = d.createElement('script');
    s.src = `//${shortName}.disqus.com/embed.js`;
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
  }
})

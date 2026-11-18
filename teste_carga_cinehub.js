// Teste inicial de carga HTTP. Use apenas em um ambiente seu e autorizado.
// Não executa scripts do navegador nem simula cliques em anúncios.
import http from 'k6/http';
import { check, sleep } from 'k6';

const baseUrl = (__ENV.BASE_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');
const paths = ['/en', '/fr', '/de', '/en/blog', '/fr/blog', '/de/blog'];

export const options = {
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<1500'],
    checks: ['rate>0.99'],
  },
};

export default function () {
  const path = paths[(__ITER + __VU) % paths.length];
  const response = http.get(`${baseUrl}${path}`, {
    headers: { 'User-Agent': 'CineHub-Authorized-Load-Test/1.0' },
    tags: { page: path },
  });

  check(response, { 'responde HTTP 200': (r) => r.status === 200 });
  sleep(1);
}

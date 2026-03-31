[🇺🇸 English](README.en.md)

# LinkAqui

Sua pagina de links, do seu jeito. Uma alternativa open source ao Linktree, construida com Laravel, Livewire e Tailwind CSS.

<p align="center">
  <img src="screenshots/profile-mobile.png" alt="Perfil publico" width="280" />
</p>

## Screenshots

<details>
<summary>Ver todas as telas</summary>

### Landing Page
![Landing Page](screenshots/landing.png)

### Page Builder
![Page Builder](screenshots/builder.png)

### Analytics
![Analytics](screenshots/analytics.png)

### Produtos
![Produtos](screenshots/products.png)

### Perfil Publico (Desktop)
![Perfil Desktop](screenshots/profile-desktop.png)

</details>

## Funcionalidades

### Para o usuario

- **Links personalizaveis** -- Adicione quantos links quiser com icones visuais, cores (RGBA com alpha/transparencia) e imagens customizadas
- **Redes sociais** -- Conecte suas redes informando apenas o username (Instagram, GitHub, Twitter, LinkedIn, TikTok, YouTube, WhatsApp, Telegram, Discord, Twitch, Spotify, E-mail, Website)
- **Embeds** -- Incorpore conteudo de YouTube, Spotify, SoundCloud, Twitch, Vimeo, TikTok e Apple Music em qualquer posicao entre os links
- **Temas** -- Escolha entre 5 temas modernos com gradientes mesh animados (Midnight Glow, Clean Light, Ocean Dark, Sunset Warm, Neon Cyber)
- **Fontes personalizaveis** -- Escolha entre 10 fontes do Google Fonts (Inter, Poppins, Montserrat, Playfair Display, etc.)
- **Page Builder** -- Interface visual interativa com preview ao vivo em formato de celular
- **Ordenacao livre** -- Links e embeds em lista unica com drag-and-drop; redes sociais podem ficar antes ou depois do conteudo (toggle)
- **Busca de Produtos** -- Sistema de produtos afiliados com codigos curtos, busca na pagina publica
- **Compartilhar** -- Copiar link da pagina com um clique e gerar QR Code para download
- **Notificacoes toast** -- Feedback visual em todas as acoes (salvar, deletar, ativar, trocar tema, etc.)
- **Otimizacao de imagens** -- Upload com resize automatico e conversao para WebP (avatars, produtos, links)
- **Analytics** -- Pagina dedicada com graficos de visitas por dia, top links, taxa de cliques e referrers, com filtros por periodo (7, 30, 90 dias ou tudo)
- **Perfil publico** -- Pagina em subdominio unico com animacoes, avatar com ring gradient, e temas dinamicos

### Produtos (Sistema de Afiliados)

- Cadastre produtos com: imagem, nome, preco original, preco promocional, URL de afiliado e codigo curto auto-gerado
- Ative a busca de produtos no Builder via toggle
- Na pagina publica, um campo de busca aparece acima dos links
- Visitantes digitam o codigo do produto → skeleton loading → card do produto com botao de compra
- Analytics de produtos: contagem de buscas e cliques por produto
- Produtos em cache (15 min), cache invalidado ao editar
- Rate limiting e validacao (regex, sem injecao)
- Top 5 produtos da semana com ranking visual

### Para o admin

- **Dashboard** -- Estatisticas do sistema com filtros por periodo, grafico de visitas, top usuarios
- **Detalhe do usuario** -- Analytics individuais, preview do linktree, informacoes completas
- **Gestao de usuarios** -- Busca, ativar/desativar, promover admin, editar dados (CRUD completo)
- **Editor visual de temas** -- CRUD completo com color pickers por secao, mini preview ao vivo
- **Bloqueio de inativos** -- Usuarios desativados perdem acesso ao login e a pagina publica

### Seguranca

- CSP headers nas paginas publicas (com permissao para YouTube, Spotify, SoundCloud, Twitch, Vimeo, TikTok, Apple Music, Google Fonts, Alpine.js)
- Rate limiting (60/min perfil, 30/min cliques e busca de produtos)
- LGPD compliance (IPs hasheados com salt diario, youtube-nocookie)
- Livewire com `#[Locked]` em IDs, queries scoped, Policies
- Middleware de bloqueio para usuarios inativos
- Embeds com sandbox e URLs reconstruidas server-side
- Busca de produtos: validacao regex, cache, rate limit

### Tecnico

- Multi-tenant via subdominio (`usuario.seudominio.com`)
- Autenticacao completa (login, registro, recuperacao de senha, verificacao de email, 2FA)
- Livewire Blaze para performance
- 3 design systems separados (profile, builder, landing)
- Otimizacao de imagens com Intervention Image (resize + WebP)
- 183+ testes automatizados (Pest)

## Stack

| Tecnologia | Versao |
|---|---|
| PHP | 8.4 |
| Laravel | 13 |
| Livewire | 4 |
| Tailwind CSS | 4 |
| Flux UI | 2 (painel admin) |
| Pest | 4 (testes) |
| MySQL | 8+ |

## Requisitos

- PHP >= 8.4
- Composer
- Node.js >= 20
- MySQL 8+
- Laravel Herd (recomendado) ou outro servidor local

## Instalacao

### 1. Clone o repositorio

```bash
git clone https://github.com/hassekf/linkaqui.git
cd linkaqui
```

### 2. Instale as dependencias

```bash
composer install
npm install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com suas configuracoes:

```env
DB_DATABASE=linkaqui
DB_USERNAME=root
DB_PASSWORD=

APP_DOMAIN=linkaqui.test
SESSION_DOMAIN=.linkaqui.test
QUEUE_CONNECTION=database
```

### 4. Execute as migrations e seeds

```bash
php artisan migrate
php artisan db:seed --class=ThemeSeeder
```

### 5. Configure o storage link

```bash
php artisan storage:link
```

### 6. Compile os assets

```bash
npm run build
```

### 7. Configure o subdominio wildcard

Para que `usuario.linkaqui.test` funcione, voce precisa configurar wildcard DNS.

#### Com Laravel Herd:

```bash
herd link linkaqui
herd secure linkaqui
```

Configure nas preferencias do Herd para que `*.linkaqui.test` aponte para `127.0.0.1`.

#### Com Valet:

```bash
valet link linkaqui
valet secure linkaqui
```

### 8. Inicie o servidor de desenvolvimento

```bash
composer run dev
```

Acesse: `http://linkaqui.test`

## Jobs e Filas

O LinkAqui usa jobs em fila para processar analytics (visitas e cliques) sem bloquear a resposta ao usuario.

### Jobs disponiveis

| Job | Descricao |
|---|---|
| `RecordVisit` | Registra visita unica (IP hasheado com salt diario, deduplicacao 24h) |
| `RecordClick` | Registra clique em link (incrementa contador desnormalizado) |

### Rodando os workers

**Em desenvolvimento**, o `composer run dev` ja inicia o queue worker automaticamente.

Para rodar manualmente:

```bash
# Worker basico
php artisan queue:work

# Worker com restart automatico ao detectar mudancas
php artisan queue:listen

# Worker em producao (recomendado com Supervisor)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

### Comando de limpeza

Dados de analytics mais antigos que 90 dias sao removidos automaticamente:

```bash
# Rodar manualmente
php artisan analytics:prune

# Em producao, ja esta agendado para rodar diariamente via scheduler
```

Para o scheduler funcionar em producao, adicione ao crontab:

```bash
* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

## Uso

### Fluxo do usuario

1. Cadastre-se em `linkaqui.test` (username vira o subdominio)
2. Acesse o **Builder** (`/builder`) para gerenciar sua pagina
3. Adicione links, redes sociais, embeds e escolha um tema e fonte
4. Cadastre produtos afiliados em **Produtos** (`/products`) e ative a busca no Builder
5. Reordene tudo com drag-and-drop na lista unificada de conteudo
6. Veja o preview ao vivo no painel direito (mobile: abaixo do conteudo)
7. Compartilhe sua pagina: `seuusuario.linkaqui.test`
8. Acompanhe estatisticas em **Analytics** (`/analytics`)

### Rotas

| Rota | Metodo | Descricao |
|---|---|---|
| `/` | GET | Landing page (visitantes) / redirect para builder (logados) |
| `/builder` | GET | Page builder com preview ao vivo |
| `/analytics` | GET | Estatisticas de visitas e cliques com filtros |
| `/products` | GET | Gestao de produtos afiliados |
| `/settings/profile` | GET | Configuracoes do perfil |
| `/settings/appearance` | GET | Aparencia |
| `/settings/security` | GET | Seguranca e 2FA |
| `/admin` | GET | Dashboard administrativo com graficos |
| `/admin/users` | GET | Gestao de usuarios (CRUD) |
| `/admin/users/{user}` | GET | Detalhe do usuario com analytics e preview |
| `/admin/themes` | GET | Editor visual de temas (CRUD) |
| `{username}.dominio/` | GET | Pagina publica do perfil |
| `{username}.dominio/click/{link}` | POST | Rastrear clique em link |
| `{username}.dominio/product/search` | GET | Buscar produto por codigo |
| `{username}.dominio/product/{code}/click` | POST | Rastrear clique em produto |

## Painel Admin

O painel admin fica em `/admin`. Para promover um usuario a admin:

```bash
php artisan tinker --execute 'App\Models\User::where("username", "seu-usuario")->update(["is_admin" => true]);'
```

## Analytics

### Visitas
- Registradas via job em fila (`RecordVisit`)
- IP hasheado com salt diario (LGPD compliance)
- Deduplicacao: mesmo IP nao conta como nova visita dentro de 24h
- Graficos de visitas por dia com filtros de periodo

### Cliques
- Registrados via job em fila (`RecordClick`)
- Contadores desnormalizados para performance
- Taxa de cliques por link

### Referrers
- Origem do trafego rastreada e exibida nos analytics

### Limpeza automatica
- Dados com mais de 90 dias sao removidos diariamente pelo comando `analytics:prune`

### Admin
- Dashboard com estatisticas globais e graficos
- Pagina de detalhe por usuario com analytics individuais e preview do linktree

## Desenvolvimento

```bash
# Rodar todos os testes
php artisan test

# Rodar um teste especifico
php artisan test --filter=ProfilePageTest

# Formatar codigo
vendor/bin/pint

# Limpar cache de views
php artisan view:clear

# Servidor de desenvolvimento (inclui queue worker, vite, e pail)
composer run dev
```

## Estrutura do Projeto

```
app/
  Concerns/             Traits (BelongsToUser, ProfileValidationRules)
  Console/Commands/     Comandos (PruneAnalyticsCommand)
  Enums/                Enums (LinkType, EmbedType, SocialPlatform)
  Http/Controllers/     Controllers (ProfilePageController, TrackClickController,
                          ProductSearchController, TrackProductClickController)
  Http/Middleware/       Middlewares (EnsureUserIsAdmin, EnsureUserIsActive, CSP)
  Jobs/                 Jobs de analytics (RecordVisit, RecordClick)
  Livewire/Admin/       Componentes admin (Dashboard, UserManager, UserDetail, ThemeManager)
  Livewire/Builder/     Componentes builder (ContentList, ProfileSettings, LivePreview,
                          Analytics, Products, etc.)
  Models/               Models (User, Link, SocialLink, Embed, Theme, Visit, Click, Product)
  Policies/             Policies (LinkPolicy, SocialLinkPolicy, EmbedPolicy)
  Services/             Services (EmbedService, AnalyticsService)
resources/
  css/app.css           Flux UI + admin (Tailwind)
  css/profile.css       Design system do perfil publico
  css/builder.css       Design system do page builder
  css/landing.css       Design system da landing page
  views/components/     Componentes Blade (builder/*, social-icon, link-icon)
  views/layouts/        Layouts (admin, builder, landing, app, auth)
  views/livewire/       Views dos componentes Livewire
  views/profile/        Pagina publica do perfil
routes/
  web.php               Rotas principais (landing, builder, analytics, products, admin)
  profile.php           Rotas do perfil publico (subdominio, product search/click)
  settings.php          Rotas de configuracoes do usuario
  console.php           Agendamento de comandos (prune analytics)
tests/
  Feature/Admin/        Testes admin (Dashboard, UserManager, UserDetail, ThemeManager)
  Feature/Auth/         Testes autenticacao e middleware
  Feature/Builder/      Testes builder (ContentList, Links, Embeds, Social, Profile, Theme)
  Feature/Profile/      Testes perfil publico e click tracking
  Feature/              Testes de produtos, analytics, landing page, etc.
  Unit/                 Testes unitarios (EmbedService, AnalyticsService)
  Arch/                 Testes de arquitetura
```

## Licenca

MIT

## Contribuindo

Contribuicoes sao bem-vindas! Abra uma issue ou envie um pull request.

<div class="container mx-auto py-6 lg:py-10">

  <div class="grid gap-6 lg:grid-cols-[3fr_5fr] items-start">

    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-primary-focus to-secondary text-primary-content shadow-sm min-h-full">

      <div class="flex flex-col justify-between h-full p-7 md:p-10">

        <div>

          <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium backdrop-blur">
            <x-icons.line.message-2 class="w-4 h-4" />
            <span>Kontak</span>
          </div>

          <h1 class="mt-5 text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight leading-tight max-w-xl">
            {{ $title }}
          </h1>

          <p class="mt-6 max-w-2xl text-sm md:text-base leading-8 text-primary-content/85">
            @lang('message.intro')
          </p>

        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2">

          <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">

            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-white/10">
              <x-icons.line.mail class="w-5 h-5" />
            </div>

            <div class="mt-4 font-semibold">
              Respon cepat
            </div>

            <p class="mt-2 text-sm leading-6 text-primary-content/80">
              Kami akan berusaha membalas pesan kamu secepat mungkin.
            </p>

          </div>

          <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">

            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-white/10">
              <x-icons.line.shield-check class="w-5 h-5" />
            </div>

            <div class="mt-4 font-semibold">
              Privasi terjaga
            </div>

            <p class="mt-2 text-sm leading-6 text-primary-content/80">
              Informasi yang kamu kirim tidak akan dibagikan ke pihak lain.
            </p>

          </div>

        </div>

      </div>

    </div>

    <div class="lg:sticky lg:top-24">

      <div class="overflow-hidden rounded-3xl border border-base-300 bg-base-100 shadow-sm">

        <div class="border-b border-base-300/70 px-6 py-5">

          <h2 class="text-xl font-semibold tracking-tight">
            Kirim pesan
          </h2>

          <p class="mt-1 text-sm text-base-content/60">
            Isi formulir berikut untuk menghubungi kami.
          </p>

        </div>

        <div class="p-6 lg:p-7">

          @if (session()->has('message'))

            <div role="alert" class="alert alert-success mb-5">
              <x-icons.line.check class="w-5 h-5" />
              <span>{{ session('message') }}</span>
            </div>

          @endif

          <form wire:submit.prevent="send" action="" method="post" novalidate>

            @csrf

            <fieldset class="fieldset space-y-5">

              <x-forms.input
                name="name"
                model="name"
                label="message.name"
                required
              />

              <x-forms.input
                type="email"
                name="email"
                model="email"
                label="message.email"
                helper="message.provide_email_if_want_reply"
              />

              <x-forms.select
                model="subject"
                name="subject"
                label="message.subject"
                required
              >

                <option value="">
                  @lang('message.select_subject')
                </option>

                @foreach ($subjects as $subject)
                  <option value="{{ $subject->getLabel() }}">
                    {{ $subject->getLabel() }}
                  </option>
                @endforeach

              </x-forms.select>

              <x-forms.textarea
                name="body"
                model="body"
                label="message.body"
                required
              />

              @guest
                <div class="pt-1">
                  <x-cloudflare-turnstile :livewire="true" />
                </div>
              @endguest

              <div class="pt-3">

                <x-forms.button
                  type="submit"
                  label="message.btn.submit"
                  class="btn-primary w-full rounded-xl h-12 text-sm font-semibold"
                />

              </div>

            </fieldset>

          </form>

        </div>

      </div>

    </div>

  </div>

</div>

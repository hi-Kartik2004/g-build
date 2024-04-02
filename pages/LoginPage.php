<section class="section">
    <div class="container h-screen flex flex-col items-center justify-center">
        <div class="card">
            <div class="card__header">
                <h4 class="underline">
                    Login
                </h4>
                <p class="">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit magni obcaecati veniam.
                </p>
            </div>

            <div class="card__content">
                <form action="./php/actions.php?login" method="post" class="flex flex-col gap-2 mt-4">
                    <input type="email" name="email" placeholder="Enter Email" class="border" required />

                    <input type="password" name="password" placeholder="Enter Password" class="border" required />

                    <button class="primary__btn mt-2">
                        Login &rarr;
                    </button>

                    <div class="separator"></div>

                    <!-- <p class="">
                        By registering you agree to our <a href="https://github.com/hi-kartik2004/g-build" target="_blank">Terms & Conditions</a>
                    </p> -->

                    <p class="mt-2">
                        Don't have an account? <a href="?page=register">Register</a>
                    </p>

                </form>

                <div class="flex w-full justify-around">



                    <!-- <a href="https://github.com/hi-kartik2004/g-build" target="_blank" class="mt-4">Github &rarr;</a>

                    <a href="https://github.com/hi-kartik2004" target="_blank" class="mt-4">Know more &rarr;</a> -->
                </div>
            </div>

            <div class="card__footer">

            </div>
        </div>

    </div>

</section>
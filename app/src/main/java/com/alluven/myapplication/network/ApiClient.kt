package com.alluven.myapplication.network

import com.google.gson.GsonBuilder
import com.alluven.myapplication.util.SessionManager
import okhttp3.Interceptor
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ApiClient {

    private const val BASE_URL = "https://192.168.15.21:8888/alluven/api/"

    fun build(session: SessionManager? = null): Retrofit {
        val gson = GsonBuilder().setLenient().create()

        val log = HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }

        val auth = Interceptor { chain ->
            val req = chain.request().newBuilder().apply {
                session?.token?.let { addHeader("Authorization", "Bearer $it") }
            }.build()
            chain.proceed(req)
        }

        val client = OkHttpClient.Builder()
            .addInterceptor(log)
            .addInterceptor(auth)
            .build()

        return Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }
}